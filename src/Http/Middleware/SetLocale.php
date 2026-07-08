<?php

namespace Islamikit\Starterkit\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $available = array_keys(config('starterkit.available_locales', ['en' => 'English']));
        $fallback = config('app.fallback_locale', 'en');

        // ✅ Langsung baca dari field user, kalau guest fallback ke default
        $locale = $request->user()?->locale ?? $fallback;

        if (!in_array($locale, $available)) {
            $locale = $fallback;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}