<?php

namespace Islamikit\Starterkit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class Menu extends Model
{
    protected $fillable = [
        'parent_id',
        'label',
        'labels',
        'route',
        'icon',
        'type',
        'order',
        'roles',
    ];

    protected $casts = [
        'roles' => 'array',
        'labels' => 'array',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id')->orderBy('order');
    }

    /**
     * Mengonversi nilai route menjadi URL valid.
     */
    public function getUrlAttribute(): string
    {
        if (empty($this->route) || $this->route === '#') {
            return '#';
        }

        if (str_starts_with($this->route, '/') || preg_match('/^https?:\/\//', $this->route)) {
            return url($this->route);
        }

        if (Route::has($this->route)) {
            return route($this->route);
        }

        return url($this->route);
    }

    public function getLocalizedLabelAttribute(): string
    {
        $locale = app()->getLocale();
        $labels = $this->labels ?? [];

        if (isset($labels[$locale]) && !empty($labels[$locale])) {
            return $labels[$locale];
        }

        if (isset($labels['en']) && !empty($labels['en'])) {
            return $labels['en'];
        }

        if (!empty($labels)) {
            return reset($labels);
        }

        return $this->attributes['label'] ?? '';
    }

    public function hasAccess($userRoles): bool
    {
        if (in_array('super-admin', $userRoles)) {
            return true;
        }

        if (empty($this->roles) || in_array('*', $this->roles)) {
            return true;
        }

        $roles = is_array($this->roles) ? $this->roles : json_decode($this->roles, true);

        if (!is_array($roles)) {
            return false;
        }

        return count(array_intersect($roles, $userRoles)) > 0;
    }

    // ════════════════════════════════════════════════════
    // CACHE: Hanya flush route-roles-mapping
    // Menu tree TIDAK di-cache (langsung query di HandleInertiaRequests)
    // ════════════════════════════════════════════════════

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('route-roles-mapping');
        });

        static::deleted(function () {
            Cache::forget('route-roles-mapping');
        });
    }

    /**
     * Menyimpan atau memperbarui data node menu secara aman.
     */
    public static function simpanAtauUpdate(array $data, ?int $id = null): self
    {
        if (isset($data['labels']) && is_array($data['labels'])) {
            $data['label'] = $data['labels']['en'] ?? reset($data['labels']) ?? $data['label'] ?? '';
        }

        if (empty($data['order'])) {
            $data['order'] = self::where('parent_id', $data['parent_id'] ?? null)->max('order') + 1;
        }

        if ($id) {
            $menu = self::findOrFail($id);
            if (isset($data['parent_id']) && $data['parent_id'] == $id) {
                $data['parent_id'] = null;
            }
            $menu->update($data);
            return $menu;
        }

        if (empty($data['icon'])) {
            $data['icon'] = "Layers";
        }

        return self::create($data);
    }

    /**
     * Nested sorting untuk root + children.
     * Menggunakan update massal via query builder untuk performa.
     */
    public static function urutkanMassal(array $items): void
    {
        \DB::transaction(function () use ($items) {
            foreach ($items as $index => $item) {
                if (!isset($item['id'])) continue;

                self::where('id', $item['id'])->update([
                    'parent_id' => null,
                    'order' => $index + 1,
                ]);

                if (isset($item['children']) && is_array($item['children'])) {
                    foreach ($item['children'] as $childIndex => $child) {
                        if (!isset($child['id'])) continue;

                        self::where('id', $child['id'])->update([
                            'parent_id' => $item['id'],
                            'order' => $childIndex + 1,
                        ]);
                    }
                }
            }
        });

        // Flush cache SETELAH transaction berhasil
        Cache::forget('route-roles-mapping');
    }

    /**
     * Menghapus node menu dengan aman (anak naik jadi root).
     */
    public function hapusNodeAman(): void
    {
        \DB::transaction(function () {
            $children = self::where('parent_id', $this->id)->get();
            $maxOrder = self::whereNull('parent_id')->max('order') ?? 0;

            foreach ($children as $i => $child) {
                $child->update([
                    'parent_id' => null,
                    'order' => $maxOrder + $i + 1,
                ]);
            }

            $this->delete();
        });
        // Cache::forget sudah otomatis via 'deleted' event di booted()
    }
}