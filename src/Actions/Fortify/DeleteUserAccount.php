<?php

namespace Islamikit\Starterkit\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Contracts\DeletesUsers;

class DeleteUserAccount implements DeletesUsers
{
    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        // Hapus avatar jika ada
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::guard('web')->logout();

        $user->delete();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}