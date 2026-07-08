<?php

namespace Islamikit\Starterkit\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ImpersonateController extends Controller
{
    /**
     * Mulai impersonate user lain.
     */
    public function start(Request $request, User $targetUser)
    {
        // Policy check sudah dilakukan di UserPolicy::impersonate
        $this->authorize('impersonate', $targetUser);

        // Cegah chain impersonation
        if (session()->has('impersonator_id')) {
            abort(403, 'Cannot start impersonation while already impersonating.');
        }

        // Simpan ID impersonator asli
        session()->put('impersonator_id', $request->user()->id);

        // Log activity
        Log::info("User {$request->user()->name} started impersonating {$targetUser->name}");

        // Login sebagai target user
        Auth::login($targetUser);

        return redirect()->route('dashboard')
            ->with('message', "You are now impersonating {$targetUser->name}.");
    }

    /**
     * Hentikan impersonate, kembali ke user asli.
     */
    public function stop(Request $request)
    {
        $impersonatorId = session()->get('impersonator_id');

        if (!$impersonatorId) {
            return redirect()->route('dashboard');
        }

        $impersonator = User::find($impersonatorId);

        if (!$impersonator) {
            // Data tidak konsisten, logout saja
            Auth::logout();
            session()->invalidate();
            return redirect()->route('login');
        }

        Log::info("User {$impersonator->name} stopped impersonating {$request->user()->name}");

        // Hapus state impersonate
        session()->forget('impersonator_id');

        // Login kembali sebagai impersonator
        Auth::login($impersonator);

        return redirect()->route('dashboard')
            ->with('message', 'Impersonation stopped. You are back to your account.');
    }
}