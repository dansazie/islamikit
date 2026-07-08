<?php

namespace Islamikit\Starterkit\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Ambil semua daftar role yang boleh diakses oleh user saat ini
     */
    protected function getAllowedRoles(): array
    {
        $currentUser = auth()->user();
        if (!$currentUser) {
            return [];
        }

        // Ambil semua nama role yang dimiliki user saat ini
        $myRoles = $currentUser->getRoleNames()->toArray();
        $hierarchy = config('starterkit.role_hierarchy', []);
        
        $allowedRoles = [];
        foreach ($myRoles as $role) {
            if (isset($hierarchy[$role])) {
                $allowedRoles = array_merge($allowedRoles, $hierarchy[$role]);
            }
        }

        return array_unique($allowedRoles);
    }

    public function index(Request $request)
    {
        $allowedRoles = $this->getAllowedRoles();

        $query = User::with('roles:name')
            ->whereDoesntHave('roles', function ($q) use ($allowedRoles) {
                $q->whereNotIn('name', $allowedRoles);
            });

        // Fitur Pencarian jika ada
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = config('starterkit.ui.pagination_default_per_page', 10);
        $paginatedUsers = $query->latest()->paginate($perPage)->withQueryString();

        return Inertia::render('Admin/User/Index', [
            'usersList' => collect($paginatedUsers->items())->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'),
                    'created_at' => $user->created_at->format('d M Y'),
                ];
            }),
            'rolesList' => Role::whereIn('name', $allowedRoles)->pluck('name'),
            'totalUsers' => $paginatedUsers->total(),
            'perPage' => $perPage,
            'urls' => [
                'index'   => route('admin.users.index'),
                'store'   => route('admin.users.store'),
                'destroy' => '/admin/users/',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $userId = $request->id;
        $allowedRoles = $this->getAllowedRoles();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'roles' => 'required|array',
            'roles.*' => ['string', Rule::in($allowedRoles)], // Proteksi: Role yang dikirim harus ada di dalam hierarki
            'password' => $userId ? 'nullable|string|min:8' : 'required|string|min:8',
        ];

        $validasi = $request->validate($rules);

        if ($userId) {
            $user = User::findOrFail($userId);

            // Proteksi tambahan: Pastikan target user yang mau diedit tidak memiliki role di atas kita
            $targetHasHigherRole = $user->roles()->whereNotIn('name', $allowedRoles)->exists();
            if ($targetHasHigherRole) {
                return redirect()->back()->with('error', 'Anda tidak memiliki otoritas untuk mengubah data pengguna ini!');
            }

            $user->name = $validasi['name'];
            $user->email = $validasi['email'];
            if (!empty($validasi['password'])) {
                $user->password = Hash::make($validasi['password']);
            }
            $user->save();
        } else {
            $user = User::create([
                'name' => $validasi['name'],
                'email' => $validasi['email'],
                'password' => Hash::make($validasi['password']),
            ]);
        }

        // Sinkronisasi Role Spatie secara aman
        $user->syncRoles($validasi['roles']);

        return redirect()->back()->with('message', 'Data pengguna dan hak akses berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        // Proteksi agar tidak menghapus akun sendiri
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak diperbolehkan menghapus akun Anda sendiri yang sedang aktif!');
        }

        // Proteksi: Pastikan target user yang dihapus tidak memiliki role di atas kita
        $allowedRoles = $this->getAllowedRoles();
        $targetHasHigherRole = $user->roles()->whereNotIn('name', $allowedRoles)->exists();
        if ($targetHasHigherRole) {
            return redirect()->back()->with('error', 'Anda tidak memiliki otoritas untuk menghapus pengguna ini!');
        }

        $user->delete();

        return redirect()->back()->with('message', 'Pengguna berhasil dihapus dari sistem!');
    }
}