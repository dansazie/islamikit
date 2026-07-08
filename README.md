# IslamiKit Starterkit

Laravel 13 Starterkit powered by **Inertia.js**, **Vue 3**, **Fortify**, and **Spatie** packages.

> A ready-to-use starter kit for building modern Laravel applications with authentication, permissions, settings, and reusable Vue components.

---

## Requirements

* PHP 8.2+
* Laravel 13
* Node.js 20+
* Composer 2

---

## Installation

Install the package via Composer.

```bash
composer require islamikit/starterkit:"^1.0.0"
```

Then run the installer.

```bash
php artisan starterkit:install
```

The installer will automatically:

* Publish configuration files
* Publish Vue pages, layouts and components
* Publish CSS assets
* Publish application root view
* Update `app.js`
* Update `HandleInertiaRequests`
* Replace `vite.config.js`
* Publish Spatie migrations
* Run database migrations
* Run package seeders
* Install frontend dependencies

Finally start Vite.

```bash
npm run dev
```

---

## Published Assets

The installer publishes the following resources into your Laravel application.

```
config/starterkit.php

resources/
├── css/
├── js/
│   ├── Components/
│   ├── Composables/
│   ├── Layouts/
│   └── Pages/
└── views/
    └── app.blade.php
```

---

## Included Packages

* Inertia.js
* Vue 3
* Laravel Fortify
* Spatie Permission
* Spatie Activitylog
* Spatie Settings

---

## Vite Configuration

The installer replaces your `vite.config.js` with the required configuration including:

* Vue Plugin
* Tailwind CSS
* unplugin-icons
* Laravel Vite Plugin
* `@` alias
* `@starterkit` alias

---

## Features

* Laravel 13 support
* Inertia.js + Vue 3
* Authentication using Laravel Fortify
* Permission management (Spatie Permission)
* Activity Log
* Application Settings
* Publishable Vue Components
* Publishable Layouts
* Publishable Pages
* Tailwind CSS support
* Auto Package Discovery

---

## Repository

https://github.com/dansazie/islamikit

---

## License

This package is open-sourced software licensed under the MIT license.
