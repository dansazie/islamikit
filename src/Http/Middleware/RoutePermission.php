<?php

namespace Islamikit\Starterkit\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Islamikit\Starterkit\Models\Menu;

class RoutePermission
{
    /**
     * Blacklist approach: Hanya cek akses untuk route yang terdaftar di tabel menus.
     * Route yang TIDAK ada di menus → diizinkan (untuk dashboard, profile, dll).
     * Super-admin → selalu diizinkan.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $routeName = $request->route()->getName();

        if (!$routeName) {
            return $next($request);
        }

        // Cek apakah module dinonaktifkan
        if ($this->isModuleDisabled($routeName)) {
            abort(404);
        }

        $userRoles = $user->getRoleNames()->toArray();

        if (in_array('super-admin', $userRoles)) {
            return $next($request);
        }

        $mapping = $this->getRouteRolesMapping();

        $baseRoute = $this->extractBaseRoute($routeName);

        if (!isset($mapping[$baseRoute])) {
            return $next($request);
        }

        $allowedRoles = $mapping[$baseRoute];

        if (empty($allowedRoles) || in_array('*', $allowedRoles)) {
            return $next($request);
        }

        if (count(array_intersect($userRoles, $allowedRoles)) === 0) {
            abort(403);
        }

        return $next($request);
    }

    private function isModuleDisabled(string $routeName): bool
    {
        $map = [
            'activity-logs' => 'activity_log',
            'settings' => 'settings',
            'roles' => 'roles_permissions',
            'permissions' => 'roles_permissions',
        ];

        foreach ($map as $keyword => $configKey) {
            if (str_contains($routeName, $keyword)) {
                $enabled = config("starterkit.modules.{$configKey}.enabled", true);
                if (!$enabled) {
                    return true;
                }
            }
        }

        return false;
    }

    private function getRouteRolesMapping(): array
    {
        return Cache::remember('route-roles-mapping', now()->addHours(6), function () {
            $mapping = [];

            Menu::whereNotNull('route')
                ->where('route', '!=', '#')
                ->get()
                ->each(function ($menu) use (&$mapping) {
                    $base = $this->extractBaseRoute($menu->route);

                    if (!isset($mapping[$base])) {
                        $mapping[$base] = [];
                    }

                    $roles = $menu->roles;

                    if (empty($roles) || in_array('*', $roles)) {
                        $mapping[$base] = ['*'];
                    } else {
                        $mapping[$base] = array_merge($mapping[$base], $roles);
                    }
                });

            return array_map('array_unique', $mapping);
        });
    }

    private function extractBaseRoute(string $routeName): string
    {
        $parts = explode('.', $routeName);
        return count($parts) >= 2
            ? $parts[0] . '.' . $parts[1]
            : $routeName;
    }
}