<?php

namespace Islamikit\Starterkit\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        if ($user) {
            activity()
                ->causedBy($user)
                ->performedOn($user)
                ->log('User logged in to the application');
        }

        $redirectUrl = $user && $user->hasRole('super-admin') ? route('admin.dashboard') : '/';
        
        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended($redirectUrl);
    }
}