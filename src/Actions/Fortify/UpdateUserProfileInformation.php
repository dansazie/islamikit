<?php

namespace Islamikit\Starterkit\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Laravel\Facades\Image;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     *
     * @throws ValidationException
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(\+62|62|0)[0-9]{8,13}$/',
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
        ], [
            'phone.regex' => 'Format nomor telepon tidak valid. Contoh: 081234567890 atau +6281234567890',
        ])->validateWithBag('updateProfileInformation');

        // Handle avatar upload dengan resize
        $avatarPath = $this->handleAvatarUpload($user, $input);

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input, $avatarPath);
        } else {
            $data = [
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'] ?? null,
            ];

            if ($avatarPath !== null) {
                $data['avatar'] = $avatarPath;
            }

            $user->forceFill($data)->save();
        }
    }

    /**
     * Handle avatar upload dan resize.
     */
    protected function handleAvatarUpload(User $user, array $input): ?string
    {
        if (!isset($input['avatar']) || !($input['avatar'] instanceof UploadedFile)) {
            return null;
        }

        // Hapus avatar lama jika ada
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $avatar = $input['avatar'];
        $filename = $user->id . '_' . time() . '.' . $avatar->getClientOriginalExtension();
        $path = $avatar->storeAs('avatars', $filename, 'public');

        // Resize ke 300x300 dengan crop center
        $fullPath = Storage::disk('public')->path($path);
        $image = Image::decodePath($fullPath);
        $image->cover(300, 300)->save();

        return $path;
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    protected function updateVerifiedUser(User $user, array $input, ?string $avatarPath): void
    {
        $data = [
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
            'phone' => $input['phone'] ?? null,
        ];

        if ($avatarPath !== null) {
            $data['avatar'] = $avatarPath;
        }

        $user->forceFill($data)->save();

        $user->sendEmailVerificationNotification();
    }
}