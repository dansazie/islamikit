<?php

return [
    'modules' => [
        'activity_log' => [
            'enabled' => env('STARTERKIT_MODULE_ACTIVITY_LOG', true),
            'middleware' => ['auth'],
            'permission' => 'view activity logs',
        ],
        'settings' => [
            'enabled' => env('STARTERKIT_MODULE_SETTINGS', true),
            'middleware' => ['auth'],
            'permission' => 'manage settings',
        ],
        'roles_permissions' => [
            'enabled' => env('STARTERKIT_MODULE_RBAC', true),
            'middleware' => ['auth'],
        ],
    ],
    'ui' => [
        'sidebar_default_collapsed' => false,
        'pagination_default_per_page' => 10,
        'date_format' => 'd/m/Y',
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Locales
    |--------------------------------------------------------------------------
    |*/
    'available_locales' => [
        'en' => 'English',
        'id' => 'Indonesia',
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Hierarchy & Visibility Rules
    |--------------------------------------------------------------------------
    | Menentukan daftar role mana saja yang dapat dilihat, dibuat, atau diubah 
    | oleh pengguna berdasarkan role yang mereka sandang saat ini.
    |*/
    'role_hierarchy' => [
        'super-admin' => ['super-admin', 'admin', 'editor'],
        'admin'       => ['admin', 'editor'],
        'editor'        => [],
    ],
];