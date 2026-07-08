<?php
// packages/starterkit/publishable.php

return [
    // Config
    'starterkit-config' => [
        'path' => config_path('starterkit.php'),
        'source' => __DIR__ . '/config/starterkit.php',
    ],

    // Root View (app.blade.php)
    'starterkit-root-view' => [
        'path' => resource_path('views/app.blade.php'),
        'source' => __DIR__ . '/resources/views/app.blade.php',
    ],

    // App.js Utama (dari stub)
    'starterkit-app-js' => [
        'path' => resource_path('js/app.js'),
        'source' => __DIR__ . '/resources/stubs/app.js.stub',
    ],

    // CSS
    'starterkit-css' => [
        'path' => resource_path('css/starterkit.css'),
        'source' => __DIR__ . '/resources/css/starterkit.css',
    ],

    // JS Entry Point (Global Components Registration)
    'starterkit-js' => [
        'path' => resource_path('js/starterkit.js'),
        'source' => __DIR__ . '/resources/js/app-starterkit.js',
    ],

    // Smart Components
    'starterkit-smart-components' => [
        'path' => resource_path('js/Components/smart'),
        'source' => __DIR__ . '/resources/js/Components/smart',
    ],

    // UI Components
    'starterkit-ui-components' => [
        'path' => resource_path('js/Components/ui'),
        'source' => __DIR__ . '/resources/js/Components/ui',
    ],

    // Composables
    'starterkit-composables' => [
        'path' => resource_path('js/Composables'),
        'source' => __DIR__ . '/resources/js/Composables',
    ],

    // Layouts
    'starterkit-layouts' => [
        'path' => resource_path('js/Layouts'),
        'source' => __DIR__ . '/resources/js/Layouts',
    ],

    // Pages
    'starterkit-pages' => [
        'path' => resource_path('js/Pages'),
        'source' => __DIR__ . '/resources/js/Pages',
    ],

    // Seeders
    'starterkit-seeders' => [
        'path' => database_path('seeders/Starterkit'),
        'source' => __DIR__ . '/database/seeders',
    ],
];