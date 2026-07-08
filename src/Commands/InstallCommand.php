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

        $this->updateUserModel();

        $this->publish('starterkit-config', $force, '📋 Configuration');

        $this->publish('starterkit-root-view', $force, '🎨 Root View');

        $this->publish('starterkit-css', $force, '📦 CSS');

        $this->publish('starterkit-js', $force, '🧩 Starterkit JS');

        $this->publish('starterkit-smart-components', $force, '💡 Smart Components');
        $this->publish('starterkit-ui-components', $force, '🎯 UI Components');
        $this->publish('starterkit-composables', $force, '🔧 Composables');
        $this->publish('starterkit-layouts', $force, '📐 Layouts');
        $this->publish('starterkit-pages', $force, '📄 Pages');

        $this->updateAppJs();
        $this->updateHandleInertiaRequests();
        $this->updateViteConfig();
        $this->updateBootstrapApp();

        $this->publishSpatieMigrations();

        if (!$this->option('skip-migrate')) {
            $this->runMigrations();
        }

        if (!$this->option('skip-seed')) {
            $this->runSeeders();
        }

        if (!$this->option('skip-npm')) {
            $this->runNpmBuild();
        }

        $this->newLine();
        $this->info('🧹 Clearing application caches...');
        Artisan::call('optimize:clear');
        $this->line('   ✓ Caches cleared');
        
        $this->newLine();
        $this->info('✅ IslamiKit Starterkit installed successfully!');
        $this->newLine();
        $this->displayPostInstallInfo();

        return self::SUCCESS;
    }

    protected function updateUserModel(): void
    {
        $this->info('👤 Updating User Model...');
        $path = app_path('Models/User.php');

        if (!File::exists($path)) {
            $this->warn('   ⚠ User model not found.');
            return;
        }

        $userStub = <<<'PHP'
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'phone', 'avatar', 'locale'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the avatar URL.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? Storage::url($this->avatar) : null;
    }
}
PHP;

        File::put($path, $userStub);
        $this->line('   ✓ User model successfully updated to full target structure');
    }

    protected function publish(string $tag, bool $force, string $label): void
    {
        $this->info("{$label}...");
        $this->call('vendor:publish', [
            '--tag' => $tag,
            '--force' => $force,
        ]);
    }

    protected function updateViteConfig(): void
    {
        $this->info('⚡ Updating vite.config.js...');
        
        $sourcePath = __DIR__ . '/../../resources/stubs/vite.config.stub';
        $targetPath = base_path('vite.config.js');

        if (!File::exists($sourcePath)) {
            $this->warn('   ⚠ vite.config.stub not found.');
            return;
        }

        File::copy($sourcePath, $targetPath);
        $this->line('   ✓ vite.config.js replaced with IslamiKit version');
    }

    protected function updateBootstrapApp(): void
    {
        $this->info('⚙ Automating bootstrap/app.php...');
        $path = base_path('bootstrap/app.php');

        if (!File::exists($path)) {
            $this->warn('   ⚠ bootstrap/app.php not found.');
            return;
        }

        $content = File::get($path);

        $inertiaMiddleware = "\n            \$middleware->web(append: [\n                \\App\\Http\\Middleware\\HandleInertiaRequests::class,\n            ]);";

        if (!Str::contains($content, 'HandleInertiaRequests::class')) {
            if (Str::contains($content, 'withMiddleware(function (Middleware $middleware) {')) {
                $content = str_replace(
                    'withMiddleware(function (Middleware $middleware) {',
                    "withMiddleware(function (Middleware \$middleware) {{$inertiaMiddleware}",
                    $content
                );
            } elseif (Str::contains($content, 'withMiddleware(function ($middleware) {')) {
                $content = str_replace(
                    'withMiddleware(function ($middleware) {',
                    "withMiddleware(function (\$middleware) {{$inertiaMiddleware}",
                    $content
                );
            }
        }

        $permissionAlias = "\n            \$middleware->alias([\n                'route.permission' => \\Islamikit\\Starterkit\\Http\\Middleware\\RoutePermission::class,\n            ]);";

        if (!Str::contains($content, 'route.permission')) {
            if (Str::contains($content, 'withMiddleware(function (Middleware $middleware) {')) {
                $content = str_replace(
                    'withMiddleware(function (Middleware $middleware) {',
                    "withMiddleware(function (Middleware \$middleware) {{$permissionAlias}",
                    $content
                );
            } elseif (Str::contains($content, 'withMiddleware(function ($middleware) {')) {
                $content = str_replace(
                    'withMiddleware(function ($middleware) {',
                    "withMiddleware(function (\$middleware) {{$permissionAlias}",
                    $content
                );
            }
        }

        File::put($path, $content);
        $this->line('   ✓ bootstrap/app.php configured with Inertia & RoutePermission');
    }

    protected function runMigrations(): void
    {
        $this->newLine();
        $this->info('🔄 Running migrations...');

        if (!$this->confirm('   Run migrations now?', true)) {
            $this->line('   ⊘ Skipped');
            return;
        }

        try {
            Artisan::call('migrate', ['--force' => true]);
            $this->line('   ✓ Migrations completed');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            if (Str::contains($msg, 'already exists')) {
                $this->line('   ⚠ Tables already exist — skipping');
            } else {
                $this->error("   ✗ Migration failed: {$msg}");
            }
        }
    }

    protected function runSeeders(): void
    {
        $this->newLine();
        $this->info('🌱 Running seeders...');

        if (!$this->confirm('   Run seeders now?', true)) {
            $this->line('   ⊘ Skipped');
            return;
        }

        $seeders = [
            \Islamikit\Starterkit\Database\Seeders\PermissionSeeder::class,
            \Islamikit\Starterkit\Database\Seeders\MenuSeeder::class,
            \Islamikit\Starterkit\Database\Seeders\SettingsSeeder::class,
            \Islamikit\Starterkit\Database\Seeders\UserSeeder::class,
        ];

        foreach ($seeders as $seederClass) {
            try {
                Artisan::call('db:seed', [
                    '--class' => $seederClass,
                    '--force' => true,
                ]);
                $this->line("   ✓ {$seederClass}");
            } catch (\Exception $e) {
                $this->warn("   ⚠ {$seederClass}: " . $e->getMessage());
            }
        }
    }

    protected function runNpmBuild(): void
    {
        $this->newLine();
        $this->info('📦 Installing frontend dependencies...');

        if (!$this->confirm('   Run npm install now?', true)) {
            $this->line('   ⊘ Skipped. Run manually: npm install && npm run dev');
            return;
        }

        $this->line('   Installing Vue, Vite Plugin, Inertia, etc...');
        exec('npm install vue @vitejs/plugin-vue @inertiajs/inertia-vue3 @inertiajs/vue3 @vueuse/core unplugin-icons tw-animate-css 2>&1', $out, $code);
        
        $this->line('   Running full npm install...');
        exec('npm install 2>&1', $out, $code2);
        
        if ($code !== 0 || $code2 !== 0) {
            $this->error('   ✗ npm install failed');
            return;
        }

        $this->line('   ✓ Dependencies installed successfully!');
        $this->newLine();
        $this->info('🚀 Next step: Run <info>npm run dev</info> to start the Vite server.');
    }

    protected function updateAppJs(): void
    {
        $this->info('⚡ Updating app.js...');
        
        $sourcePath = __DIR__ . '/../../resources/stubs/app.js.stub';
        $targetPath = resource_path('js/app.js');

        if (!File::exists($sourcePath)) {
            $this->warn('   ⚠ app.js.stub not found.');
            return;
        }

        File::copy($sourcePath, $targetPath);
        $this->line('   ✓ app.js replaced with IslamiKit version');
    }

    protected function updateHandleInertiaRequests(): void
    {
        $this->info('🔗 Updating HandleInertiaRequests middleware...');
        
        $sourcePath = __DIR__ . '/../../resources/stubs/HandleInertiaRequests.stub';
        $targetPath = app_path('Http/Middleware/HandleInertiaRequests.php');

        if (!File::exists($sourcePath)) {
            $this->warn('   ⚠ HandleInertiaRequests stub not found.');
            return;
        }

        File::ensureDirectoryExists(app_path('Http/Middleware'));

        File::copy($sourcePath, $targetPath);
        $this->line('   ✓ HandleInertiaRequests.php updated');
    }

    protected function publishSpatieMigrations(): void
    {
        $this->info('📦 Publishing Spatie package migrations...');

        $this->call('vendor:publish', [
            '--provider' => 'Spatie\Permission\PermissionServiceProvider',
            '--force' => true,
        ]);

        $this->call('vendor:publish', [
            '--provider' => 'Spatie\Activitylog\ActivitylogServiceProvider',
            '--tag' => 'activitylog-migrations',
            '--force' => true,
        ]);

        $this->line('   ✓ Spatie migrations & config published');
    }

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