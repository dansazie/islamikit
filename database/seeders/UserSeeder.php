<?php

namespace Islamikit\Starterkit\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user Super Admin jika belum ada
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@islamikit.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('islamikit'),
                'locale' => 'id',
            ]
        );

        // Ambil role super-admin dan assign ke user
        $role = Role::where('name', 'super-admin')->first();
        if ($role) {
            $superAdmin->assignRole($role);
        }

        $this->command->info('Super Admin user created (Email: superadmin@islamikit.com | Password: islamikit)');
    }
}