<?php

namespace Islamikit\Starterkit;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Islamikit\Starterkit\Models\Menu;
use Islamikit\Starterkit\Policies\BackupPolicy;
use Islamikit\Starterkit\Policies\MenuPolicy;
use Islamikit\Starterkit\Policies\PermissionPolicy;
use Islamikit\Starterkit\Policies\RolePolicy;
use Islamikit\Starterkit\Policies\SettingPolicy;
use Islamikit\Starterkit\Policies\UserPolicy;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Laravel\Fortify\Fortify;
use Illuminate\Http\Request;

class StarterkitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \Islamikit\Starterkit\Http\Responses\LoginResponse::class
        );

        $this->app->singleton(
            \Laravel\Fortify\Contracts\LogoutResponse::class,
            \Islamikit\Starterkit\Http\Responses\LogoutResponse::class
        );
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/starterkit.php', 'starterkit');

        Fortify::loginView(function () {
            return Inertia::render('Auth/Login');
        });
        Fortify::registerView(function () {
            return Inertia::render('Auth/Register');
        });
        Fortify::requestPasswordResetLinkView(function () {
            return Inertia::render('Auth/ForgotPassword');
        });
        Fortify::resetPasswordView(function (Request $request) {
            return Inertia::render('Auth/ResetPassword', ['request' => $request]);
        });

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'starterkit');
        
        $langPath = __DIR__ . '/../resources/lang';
        $this->loadTranslationsFrom($langPath, 'starterkit');
        $this->loadJsonTranslationsFrom($langPath);

        Inertia::share('translations', function () {
            $locale = app()->getLocale();
            $fallbackLocale = config('app.fallback_locale', 'en');
            $langPath = realpath(__DIR__ . '/../resources/lang');
            
            if ($langPath && is_dir($langPath)) {
                $file = $langPath . '/' . $locale . '.json';
                if (file_exists($file)) {
                    return json_decode(file_get_contents($file), true) ?? [];
                }

                $fallbackFile = $langPath . '/' . $fallbackLocale . '.json';
                if (file_exists($fallbackFile)) {
                    return json_decode(file_get_contents($fallbackFile), true) ?? [];
                }
            }

            return [];
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\InstallCommand::class,
            ]);

            $this->registerPublishables();
        }

        Gate::policy(Menu::class, MenuPolicy::class);
        Gate::policy(\App\Models\User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy('setting', SettingPolicy::class);
        Gate::policy('backup', BackupPolicy::class);

        $router = $this->app['router'];
        $router->aliasMiddleware('route.permission', Http\Middleware\RoutePermission::class);
        $router->aliasMiddleware('impersonate.guard', Http\Middleware\PreventChainImpersonation::class);

        $this->app->booted(function () {
            $this->app['router']->pushMiddlewareToGroup('web', Http\Middleware\SetLocale::class);
        });
    }

    protected function registerPublishables(): void
    {
        $publishables = require __DIR__ . '/../publishable.php';

        foreach ($publishables as $tag => $item) {
            $this->publishes(
                [$item['source'] => $item['path']],
                $tag
            );
        }

        $all = [];
        foreach ($publishables as $item) {
            $all[$item['source']] = $item['path'];
        }
        $this->publishes($all, 'starterkit');
    }
}