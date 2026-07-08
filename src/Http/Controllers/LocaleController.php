<?php

namespace Islamikit\Starterkit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocaleController extends Controller
{
    public function switch(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|size:2',
        ]);

        $locale = $request->input('locale');
        $available = array_keys(config('starterkit.available_locales', ['en' => 'English']));

        if (!in_array($locale, $available)) {
            abort(400, 'Invalid locale.');
        }

        // Simpan langsung ke DB jika login
        if (Auth::check()) {
            Auth::user()->update(['locale' => $locale]);
        }

        return back();
    }
}