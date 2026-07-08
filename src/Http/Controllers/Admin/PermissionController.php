<?php

namespace Islamikit\Starterkit\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        Gate::authorize('view permissions');

        $permissions = Permission::when(request('search'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->latest()
            ->paginate(10)
            ->through(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'group' => explode('.', $p->name)[0] ?? 'other',
            ]);

        return Inertia::render('Admin/Permission/Index', [
            'permissionsList' => $permissions->items(),
            'totalPermissions' => $permissions->total(),
            'perPage' => $permissions->perPage(),
            'urls' => [
                'index'   => route('admin.permissions.index'),
                'store'   => route('admin.permissions.store'),
                'update'  => '/admin/permissions/',
                'destroy' => '/admin/permissions/',
            ],
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create permissions');

        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name|max:255',
        ]);

        Permission::create(['name' => $validated['name']]);

        return back()->with('message', 'Permission created successfully.');
    }

    public function update(Request $request, Permission $permission)
    {
        Gate::authorize('edit permissions', $permission);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $validated['name']]);

        return back()->with('message', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        Gate::authorize('delete permissions', $permission);

        $permission->delete();

        return back()->with('message', 'Permission deleted successfully.');
    }
}