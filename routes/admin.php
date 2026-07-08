<?php

use Illuminate\Support\Facades\Route;
use Islamikit\Starterkit\Http\Controllers\Admin\MenuController;
use Islamikit\Starterkit\Http\Controllers\Admin\UserController;
use Islamikit\Starterkit\Http\Controllers\Admin\RoleController;
use Islamikit\Starterkit\Http\Controllers\Admin\PermissionController;
use Islamikit\Starterkit\Http\Controllers\Admin\ActivityLogController;
use Islamikit\Starterkit\Http\Controllers\Admin\SettingController;
use Islamikit\Starterkit\Http\Controllers\Admin\BackupController;

Route::middleware(['web', 'auth', 'route.permission'])->prefix('admin')->group(function () {
    // Menu Management
    Route::get('/menus', [MenuController::class, 'index'])->name('admin.menus.index');
    Route::post('/menus', [MenuController::class, 'store'])->name('admin.menus.store');
    Route::post('/menus/reorder', [MenuController::class, 'reorder'])->name('admin.menus.reorder');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('admin.menus.destroy');

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Role Management
    Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');

    // Permission Management
    Route::get('/permissions', [PermissionController::class, 'index'])->name('admin.permissions.index');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('admin.permissions.store');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('admin.permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('admin.permissions.destroy');

    // Activity Log
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs.index');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

    // Backup
    Route::get('/backups', [BackupController::class, 'index'])->name('admin.backups.index');
    Route::post('/backups', [BackupController::class, 'create'])->name('admin.backups.create');
    Route::get('/backups/{file}/download', [BackupController::class, 'download'])->name('admin.backups.download');
    Route::delete('/backups/{file}', [BackupController::class, 'destroy'])->name('admin.backups.destroy');
});