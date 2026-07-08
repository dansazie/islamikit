<?php

namespace Islamikit\Starterkit\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        Gate::authorize('view roles');

        $roles = Role::with('permissions')
            ->when(request('search'), fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->withCount('users')
            ->latest()
            ->paginate(10);

        $allPermissions = Permission::all()->groupBy(fn($p) => explode('.', $p->name)[0] ?? 'other');

        return Inertia::render('Admin/Role/Index', [
            'rolesList' => $roles->items(),
            'permissionsGrouped' => $allPermissions,
            'totalRoles' => $roles->total(),
            'perPage' => $roles->perPage(),
            'urls' => [
                'index'   => route('admin.roles.index'),
                'store'   => route('admin.roles.store'),
                // String path — Vue akan append ID
                'update'  => '/admin/roles/',
                'destroy' => '/admin/roles/',
            ],
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create roles');

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return back()->with('message', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        Gate::authorize('edit roles', $role);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        } else {
            $role->syncPermissions([]);
        }

        return back()->with('message', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        Gate::authorize('delete roles', $role);

        $role->delete();

        return back()->with('message', 'Role deleted successfully.');
    }
}