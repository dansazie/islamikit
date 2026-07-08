<?php

namespace Islamikit\Starterkit\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    private array $permissions = [
        'view menus', 'create menus', 'edit menus', 'delete menus',
        'view users', 'create users', 'edit users', 'delete users', 'impersonate users',
        'view roles', 'create roles', 'edit roles', 'delete roles',
        'view permissions', 'create permissions', 'edit permissions', 'delete permissions',
        'view activity logs',
        'view settings', 'edit settings',
        'view backups', 'create backups', 'download backups', 'delete backups',
    ];

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions($this->permissions);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $adminPermissions = collect($this->permissions)
            ->reject(fn($p) => in_array($p, ['impersonate users', 'delete backups']))
            ->values()->toArray();
        $admin->syncPermissions($adminPermissions);

        $editor = Role::firstOrCreate(['name' => 'editor']);
        $editorPermissions = collect($this->permissions)
            ->filter(fn($p) => str_starts_with($p, 'view '))
            ->values()->toArray();
        $editor->syncPermissions($editorPermissions);

        $this->command->info('Permissions seeded: ' . count($this->permissions) . ' permissions created.');
    }
}