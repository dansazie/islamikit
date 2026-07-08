<?php

namespace Islamikit\Starterkit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    protected $signature = 'starterkit:install
        {--force : Overwrite existing files}
        {--skip-migrate : Skip database migration}
        {--skip-seed : Skip database seeding}
        {--skip-npm : Skip npm install and build}
    ';

    protected $description = 'Install IslamiKit Starterkit';

    public function handle(): int
    {
        $this->info('🚀 Installing IslamiKit Starterkit...');
        $this->newLine();

        $force = $this->option('force');

        // 1. Update User Model (Tambahkan 'locale' ke fillable)
        $this->updateUserModel();

        // 2. Publish Config
        $this->publish('starterkit-config', $force, '📋 Configuration');

        // 3. Publish Root View (app.blade.php)
        $this->publish('starterkit-root-view', $force, '🎨 Root View');

        // 4. Publish CSS
        $this->publish('starterkit-css', $force, '📦 CSS');


        // 6. Publish Starterkit JS (Global Component Registration)
        $this->publish('starterkit-js', $force, '🧩 Starterkit JS');

        // 7. Publish Components, Composables, Layouts, Pages
        $this->publish('starterkit-smart-components', $force, '💡 Smart Components');
        $this->publish('starterkit-ui-components', $force, '🎯 UI Components');
        $this->publish('starterkit-composables', $force, '🔧 Composables');
        $this->publish('starterkit-layouts', $force, '📐 Layouts');
        $this->publish('starterkit-pages', $force, '📄 Pages');

        // 10. Force overwrite critical files
        $this->updateAppJs();
        $this->updateHandleInertiaRequests();

        // 9. Update vite.config.js
        $this->updateViteConfig();

        // 11. Publish Spatie Migrations (WAJIB SEBELUM MIGRATE)
        $this->publishSpatieMigrations();


        // 10. Run migrations
        if (!$this->option('skip-migrate')) {
            $this->runMigrations();
        }

        // 11. Run seeders
        if (!$this->option('skip-seed')) {
            $this->runSeeders();
        }

        // 12. NPM build
        if (!$this->option('skip-npm')) {
            $this->runNpmBuild();
        }

        $this->newLine();
        $this->info('✅ IslamiKit Starterkit installed successfully!');
        $this->newLine();
        $this->displayPostInstallInfo();

        return self::SUCCESS;
    }

    // ==========================================
    // UPDATE USER MODEL (WAJIB UNTUK DB LOCALE)
    // ==========================================
    protected function updateUserModel(): void
    {
        $this->info('👤 Updating User Model...');
        $path = app_path('Models/User.php');

        if (!File::exists($path)) {
            $this->warn('  ⚠ User model not found. Please add \'locale\' to $fillable manually.');
            return;
        }

        $content = File::get($path);

        // Handle Laravel 11+ Modern Syntax: #[Fillable(['name', 'email', ...])]
        if (Str::contains($content, '#[Fillable(')) {
            if (!Str::contains($content, "'locale'")) {
                $content = preg_replace(
                    "/#\[Fillable\(\[(.*?)\]\)\]/",
                    "#[Fillable([$1, 'locale'])]",
                    $content
                );
                File::put($path, $content);
                $this->line('  ✓ Added \'locale\' to User model #[Fillable]');
            } else {
                $this->line('  ✓ User model already has \'locale\'');
            }
        } 
        // Handle Legacy Syntax: protected $fillable = [...]
        elseif (Str::contains($content, 'protected $fillable')) {
            if (!Str::contains($content, "'locale'")) {
                $content = preg_replace(
                    "/protected \$fillable\s*=\s*\[(.*?)\];/s",
                    "protected \$fillable = [$1, 'locale'];",
                    $content
                );
                File::put($path, $content);
                $this->line('  ✓ Added \'locale\' to User model $fillable');
            } else {
                $this->line('  ✓ User model already has \'locale\'');
            }
        } else {
            $this->warn('  ⚠ Could not determine $fillable format. Add \'locale\' manually.');
        }
    }

    // ==========================================
    // PUBLISH
    // ==========================================
    protected function publish(string $tag, bool $force, string $label): void
    {
        $this->info("{$label}...");
        $this->call('vendor:publish', [
            '--tag' => $tag,
            '--force' => $force,
        ]);
    }

    // ==========================================
    // UPDATE VITE CONFIG
    // ==========================================
    protected function updateViteConfig(): void
    {
        $this->info('⚡ Updating vite.config.js...');
        $path = base_path('vite.config.js');

        if (!File::exists($path)) {
            $this->warn('  ⚠ vite.config.js not found');
            return;
        }

        $content = File::get($path);

        if (Str::contains($content, "'resources/js/starterkit.js'")) {
            $this->line('  ✓ Already includes starterkit.js');
            return;
        }

        // Tambahkan starterkit.js ke array input Vite
        $content = preg_replace(
            "/(input:\s*\[)(.*?)(\])/s",
            "$1$2, 'resources/js/starterkit.js'$3",
            $content
        );

        File::put($path, $content);
        $this->line('  ✓ vite.config.js updated');
    }

    // ==========================================
    // MIGRATIONS
    // ==========================================
    protected function runMigrations(): void
    {
        $this->newLine();
        $this->info('🔄 Running migrations...');

        if (!$this->confirm('  Run migrations now?', true)) {
            $this->line('  ⊘ Skipped');
            return;
        }

        try {
            Artisan::call('migrate', ['--force' => true]);
            $this->line('  ✓ Migrations completed');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            if (Str::contains($msg, 'already exists')) {
                $this->line('  ⚠ Tables already exist — skipping');
            } else {
                $this->error("  ✗ Migration failed: {$msg}");
            }
        }
    }

    // ==========================================
    // SEEDERS
    // ==========================================
    protected function runSeeders(): void
    {
        $this->newLine();
        $this->info('🌱 Running seeders...');

        if (!$this->confirm('  Run seeders now?', true)) {
            $this->line('  ⊘ Skipped');
            return;
        }

        // ✅ GUNAKAN NAMESPACE PACKAGE LANGSUNG
        $seeders = [
            \Islamikit\Starterkit\Database\Seeders\PermissionSeeder::class,
            \Islamikit\Starterkit\Database\Seeders\MenuSeeder::class,
            \Islamikit\Starterkit\Database\Seeders\SettingsSeeder::class,
            \Islamikit\Starterkit\Database\Seeders\UserSeeder::class, // Harus paling akhir
        ];

        foreach ($seeders as $seederClass) {
            try {
                Artisan::call('db:seed', [
                    '--class' => $seederClass,
                    '--force' => true,
                ]);
                $this->line("  ✓ {$seederClass}");
            } catch (\Exception $e) {
                $this->warn("  ⚠ {$seederClass}: " . $e->getMessage());
            }
        }
    }

    // ==========================================
    // NPM BUILD
    // ==========================================
    protected function runNpmBuild(): void
    {
        $this->newLine();
        $this->info('📦 Building frontend assets...');

        if (!$this->confirm('  Run npm install && npm run build now?', true)) {
            $this->line('  ⊘ Skipped. Run manually: npm install vue @inertiajs/inertia-vue3 @inertiajs/vue3 @vueuse/core && npm install && npm run build');
            return;
        }

        // ✅ 1. Install package NPM yang dibutuhkan starterkit
        $this->line('  Installing required NPM packages (Vue, Inertia, etc)...');
        exec('npm install vue @inertiajs/inertia-vue3 @inertiajs/vue3 @vueuse/core 2>&1', $out, $code);
        if ($code !== 0) {
            $this->error('  ✗ NPM package installation failed');
            return;
        }

        // ✅ 2. Install dependency lainnya
        $this->line('  Running npm install...');
        exec('npm install 2>&1', $out, $code);
        if ($code !== 0) {
            $this->error('  ✗ npm install failed');
            return;
        }

        // ✅ 3. Build
        $this->line('  Running npm run build...');
        exec('npm run build 2>&1', $out, $code);
        if ($code !== 0) {
            $this->error('  ✗ npm run build failed');
            return;
        }

        $this->line('  ✓ Frontend built successfully');
    }

    // ==========================================
    // UPDATE APP JS (FORCE OVERWRITE)
    // ==========================================
    protected function updateAppJs(): void
    {
        $this->info('⚡ Updating app.js...');
        
        $sourcePath = __DIR__ . '/../../resources/stubs/app.js.stub';
        $targetPath = resource_path('js/app.js');

        if (!File::exists($sourcePath)) {
            $this->warn('  ⚠ app.js.stub not found.');
            return;
        }

        // Paksa timpa file bawaan Laravel
        File::copy($sourcePath, $targetPath);
        $this->line('  ✓ app.js replaced with IslamiKit version');
    }

    // ==========================================
    // UPDATE HANDLE INERTIA REQUESTS
    // ==========================================
    protected function updateHandleInertiaRequests(): void
    {
        $this->info('🔗 Updating HandleInertiaRequests middleware...');
        
        $sourcePath = __DIR__ . '/../../resources/stubs/HandleInertiaRequests.stub';
        $targetPath = app_path('Http/Middleware/HandleInertiaRequests.php');

        if (!File::exists($sourcePath)) {
            $this->warn('  ⚠ HandleInertiaRequests stub not found.');
            return;
        }

        // Paksa timpa file bawaan Laravel
        File::copy($sourcePath, $targetPath);
        $this->line('  ✓ HandleInertiaRequests.php updated');
    }

    // ==========================================
    // PUBLISH SPATIE MIGRATIONS
    // ==========================================
    protected function publishSpatieMigrations(): void
    {
        $this->info('📦 Publishing Spatie package migrations...');

        // 1. Spatie Permission Migrations
        $this->call('vendor:publish', [
            '--tag' => 'spatie-permission-migrations',
            '--force' => true,
        ]);

        // 2. Spatie Settings Migrations
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\LaravelSettings\LaravelSettingsServiceProvider',
            '--tag' => 'settings-migrations',
            '--force' => true,
        ]);

        $this->line('  ✓ Spatie migrations published to database/migrations');
    }

    // ==========================================
    // POST INSTALL INFO
    // ==========================================
    protected function displayPostInstallInfo(): void
    {
        $this->line('<comment>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</comment>');
        $this->line('<comment>Next steps:</comment>');
        $this->line('  1. <info>npm run dev</info> to start development');
        $this->line('  2. Create a user, then visit <info>' . url('/login') . '</info>');
        $this->line('');
        $this->line('<comment>Component prefixes:</comment>');
        $this->line('  Smart: <info>&lt;SmartButton&gt;</info> <info>&lt;SmartTable&gt;</info>');
        $this->line('  UI:    <info>&lt;Dropdown&gt;</info> <info>&lt;Toast&gt;</info>');
        $this->line('<comment>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</comment>');
    }
}