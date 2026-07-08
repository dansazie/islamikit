<?php

namespace Islamikit\Starterkit\Http\Controllers;

use Islamikit\Starterkit\Actions\Fortify\DeleteUserAccount;
use Islamikit\Starterkit\Actions\Fortify\UpdateUserProfileInformation;
use Islamikit\Starterkit\Actions\Fortify\UpdateUserPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;


class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil.
     */
    public function edit(Request $request)
    {
        return Inertia::render('Profile');
    }

    /**
     * Update informasi profil.
     */
    public function update(Request $request)
    {
        app(UpdateUserProfileInformation::class)->update(
            $request->user(),
            $request->all()
        );

        return back()->with('message', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        app(UpdateUserPassword::class)->update(
            $request->user(),
            $request->all()
        );

        return back()->with('message', 'Password berhasil diperbarui.');
    }

    /**
     * Hapus akun.
     */
    public function destroy(Request $request)
    {
        app(DeleteUserAccount::class)->delete($request->user());

        return redirect('/');
    }
}