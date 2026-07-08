<?php

namespace Islamikit\Starterkit\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BackupController extends Controller
{
    public function index()
    {
        Gate::authorize('view backups');

        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0] ?? 'local');
        $backupPath = config('laravel-backup.backup.destination.path_prefix', 'Laravel');

        if (!$disk->exists($backupPath)) {
            return inertia('Admin/Backup/Index', ['backups' => []]);
        }

        $files = collect($disk->files($backupPath))
            ->filter(fn($file) => Str::endsWith($file, '.zip'))
            ->map(fn($file) => [
                'file_name' => basename($file),
                'path' => $file,
                'size' => $disk->size($file),
                'size_human' => $this->formatBytes($disk->size($file)),
                'last_modified' => $disk->lastModified($file),
                'last_modified_human' => date('Y-m-d H:i:s', $disk->lastModified($file)),
            ])
            ->sortByDesc('last_modified')
            ->values();

        return inertia('Admin/Backup/Index', [
            'backups' => $files,
        ]);
    }

    public function create()
    {
        Gate::authorize('create backups');

        try {
            Artisan::call('backup:run', ['--only-files' => false, '--only-db' => false]);
            return back()->with('message', 'Backup created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function download(Request $request, string $file)
    {
        Gate::authorize('download backups');

        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0] ?? 'local');
        $backupPath = config('laravel-backup.backup.destination.path_prefix', 'Laravel');
        $fullPath = $backupPath . '/' . $file;

        if (!$disk->exists($fullPath)) {
            abort(404);
        }

        return $disk->download($fullPath);
    }

    public function destroy(Request $request, string $file)
    {
        Gate::authorize('delete backups');

        $disk = Storage::disk(config('laravel-backup.backup.destination.disks')[0] ?? 'local');
        $backupPath = config('laravel-backup.backup.destination.path_prefix', 'Laravel');
        $fullPath = $backupPath . '/' . $file;

        if (!$disk->exists($fullPath)) {
            abort(404);
        }

        $disk->delete($fullPath);

        return back()->with('message', 'Backup deleted successfully.');
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}