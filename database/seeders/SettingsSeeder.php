<?php

namespace Islamikit\Starterkit\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            [
                'group' => 'general',
                'name' => 'app_name',
                'payload' => json_encode(config('app.name', 'Laravel')),
                'locked' => 0,
            ],
            [
                'group' => 'general',
                'name' => 'app_description',
                'payload' => json_encode(''),
                'locked' => 0,
            ],
            [
                'group' => 'general',
                'name' => 'date_format',
                'payload' => json_encode('d/m/Y'),
                'locked' => 0,
            ],
        ];

        foreach ($defaults as $row) {
            DB::table('settings')->updateOrInsert(
                ['group' => $row['group'], 'name' => $row['name']],
                [
                    'payload' => $row['payload'],
                    'locked' => $row['locked'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->command->info('Default settings seeded.');
    }
}