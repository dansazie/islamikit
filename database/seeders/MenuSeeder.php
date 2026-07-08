<?php

namespace Islamikit\Starterkit\Database\Seeders;

use Illuminate\Database\Seeder;
use Islamikit\Starterkit\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus menu lama agar tidak duplikat saat re-seed
        Menu::truncate();

        $menus = [
            [
                'id' => 1, 
                'parent_id' => null, 
                'label' => 'Dashboard', 
                'labels' => null, // Tidak ada terjemahan
                'route' => 'dashboard', 
                'icon' => 'Dashboard', 
                'type' => 'link', 
                'order' => 1, 
                'roles' => ['*'] // ✅ Array PHP, bukan string JSON
            ],
            [
                'id' => 2, 
                'parent_id' => null, 
                'label' => 'User Management', 
                'labels' => [ // ✅ Array PHP asli
                    'en' => 'User Management', 
                    'id' => 'Manajemen User'
                ], 
                'route' => '#', 
                'icon' => 'Users', 
                'type' => 'link', 
                'order' => 3, 
                'roles' => ['super-admin', 'admin'] // ✅ Array PHP asli
            ],
            [
                'id' => 3, 
                'parent_id' => 2, 
                'label' => 'Users List', 
                'labels' => null, 
                'route' => '/admin/users', 
                'icon' => 'Layers', 
                'type' => 'link', 
                'order' => 1, 
                'roles' => ['super-admin', 'admin']
            ],
            [
                'id' => 4, 
                'parent_id' => null, 
                'label' => 'System Settings', 
                'labels' => [ // ✅ Array PHP asli
                    'en' => 'System Settings', 
                    'id' => 'Pengaturan Sistem'
                ], 
                'route' => '/admin/settings', 
                'icon' => 'Settings', 
                'type' => 'link', 
                'order' => 4, 
                'roles' => ['super-admin']
            ],
            [
                'id' => 5, 
                'parent_id' => null, 
                'label' => 'Menu Management', 
                'labels' => null, 
                'route' => '/admin/menus', 
                'icon' => 'Layers', 
                'type' => 'link', 
                'order' => 2, 
                'roles' => ['super-admin']
            ],
            [
                'id' => 6, 
                'parent_id' => 2, 
                'label' => 'Role List', 
                'labels' => [ // ✅ Array PHP asli
                    'en' => 'Role List', 
                    'id' => 'Daftar Rule'
                ], 
                'route' => '/admin/roles', 
                'icon' => 'Users', 
                'type' => 'link', 
                'order' => 2, 
                'roles' => ['super-admin']
            ],
            [
                'id' => 7, 
                'parent_id' => 2, 
                'label' => 'Activity Logs', 
                'labels' => [ // ✅ Array PHP asli
                    'en' => 'Activity Logs', 
                    'id' => 'Log Aktivitas'
                ], 
                'route' => '/admin/activity-logs', 
                'icon' => 'Setting', 
                'type' => 'link', 
                'order' => 4, 
                'roles' => ['super-admin']
            ],
            [
                'id' => 8, 
                'parent_id' => 2, 
                'label' => 'Permission List', 
                'labels' => [ // ✅ Array PHP asli
                    'en' => 'Permission List', 
                    'id' => 'Daftar Permisi'
                ], 
                'route' => '/admin/permissions', 
                'icon' => 'Users', 
                'type' => 'link', 
                'order' => 3, 
                'roles' => ['super-admin']
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }

        $this->command->info('Menus seeded successfully.');
    }
}