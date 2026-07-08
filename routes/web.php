<?php

use Illuminate\Support\Facades\Route;
use Islamikit\Starterkit\Http\Controllers\ProfileController;
use Islamikit\Starterkit\Http\Controllers\ImpersonateController;
use Islamikit\Starterkit\Http\Controllers\LocaleController;
use Islamikit\Starterkit\Http\Controllers\NotificationController;
use Inertia\Inertia;

// WAJIB: Bungkus dengan middleware 'web' agar session jalan
Route::middleware('web')->group(function () {

    Route::get('/', function () {
        return redirect()->route('login');
    });

    // Toast test routes (hapus di production)
    Route::get('/test-toast-success', function () {
        return redirect('/dashboard')->with('message', 'Selamat! Data berhasil disimpan ke dalam database.');
    });

    Route::get('/test-toast-error', function () {
        return redirect('/dashboard')->with('error', 'Gagal menyimpan data. Terjadi kesalahan pada server internal.');
    });

    // Locale switching (bisa diakses tanpa auth)
    Route::post('/locale', [LocaleController::class, 'switch'])->name('locale.switch');

    // Impersonation stop (harus di luar group auth)
    Route::post('/impersonate/stop', [ImpersonateController::class, 'stop'])
        ->name('impersonate.stop')
        ->middleware('auth');

    // Authenticated routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');

        // Impersonation start
        Route::post('/impersonate/{user}', [ImpersonateController::class, 'start'])
            ->name('impersonate.start')
            ->middleware(['impersonate.guard']);
    });

});