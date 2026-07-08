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
    public function index()
    {
        return Inertia::render('Admin/User/Index', [
            'usersList' => User::with('roles:name')->latest()->get()->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'),
                    'created_at' => $user->created_at->format('d M Y'),
                ];
            }),
            'rolesList' => Role::pluck('name'),
            // ← TAMBAHKAN INI
            'urls' => [
                'store'  => route('admin.users.store'),
                'destroy' => '/admin/users/',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $userId = $request->id;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'roles' => 'required|array',
            'password' => $userId ? 'nullable|string|min:8' : 'required|string|min:8',
        ];

        $validasi = $request->validate($rules);

        // Aksi Simpan atau Update
        if ($userId) {
            $user = User::findOrFail($userId);
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

        // Sinkronisasi Role Spatie secara reaktif
        $user->syncRoles($validasi['roles']);

        return redirect()->back()->with('message', 'Data pengguna dan hak akses berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        // Proteksi agar tidak menghapus akun sendiri secara tidak sengaja
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak diperbolehkan menghapus akun Anda sendiri yang sedang aktif!');
        }

        $user->delete();

        return redirect()->back()->with('message', 'Pengguna berhasil dihapus dari sistem!');
    }
}