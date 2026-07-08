<?php

namespace Islamikit\Starterkit\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Islamikit\Starterkit\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class MenuController extends Controller
{
    public function index()
    {
        Gate::authorize('view menus');

        return Inertia::render('Admin/Menu/Index', [
            'menusList' => Menu::root()
                ->with(['children' => function ($query) {
                    $query->orderBy('order');
                }])
                ->get(),
            'rolesList' => Role::pluck('name'),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create menus');

        $labelsRules = [];
        foreach (config('app.available_locales', ['en' => 'English']) as $code => $name) {
            $labelsRules["labels.{$code}"] = 'required|string|max:255';
        }

        $validasi = $request->validate(array_merge([
            'id' => 'nullable|integer',
            'parent_id' => 'nullable|exists:menus,id',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'roles' => 'nullable|array',
        ], $labelsRules));

        Menu::simpanAtauUpdate($validasi, $request->id);

        return back()->with('message', 'Data menu berhasil diproses!');
    }

    public function reorder(Request $request)
    {
        Gate::authorize('edit menus');

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.order' => 'required|integer',
            'items.*.children' => 'nullable|array',
            'items.*.children.*.id' => 'nullable|exists:menus,id',
        ]);

        Menu::urutkanMassal($request->items);

        return back()->with('message', 'Urutan posisi menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        Gate::authorize('delete menus');

        $menu->hapusNodeAman();

        return back()->with('message', 'Node menu berhasil dihapus!');
    }
}