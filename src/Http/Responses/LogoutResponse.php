<?php

namespace Islamikit\Starterkit\Http\Responses;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        if ($user) {
            activity()
                ->causedBy($user)
                ->performedOn($user)
                ->log('User logged out from the application');
        }
        
        return Inertia::location(url('/login'));
    }
}