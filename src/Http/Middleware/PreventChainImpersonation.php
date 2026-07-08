<?php

namespace Islamikit\Starterkit\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventChainImpersonation
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('impersonator_id')) {
            abort(403, 'Cannot impersonate another user while already impersonating.');
        }

        return $next($request);
    }
}