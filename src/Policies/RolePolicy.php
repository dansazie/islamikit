<?php

namespace Islamikit\Starterkit\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Islamikit\Starterkit\Traits\HandlesSuperAdmin;

class RolePolicy
{
    use HandlesSuperAdmin;

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('view roles');
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('create roles');
    }

    public function update(User $user, Role $role): bool
    {
        // Jangan izinkan edit role super-admin kecuali super-admin sendiri
        if ($role->name === 'super-admin' && !$this->isSuperAdmin($user)) {
            return false;
        }
        return $this->isSuperAdmin($user) || $user->can('edit roles');
    }

    public function delete(User $user, Role $role): bool
    {
        // Jangan izinkan hapus role super-admin
        if ($role->name === 'super-admin') {
            return false;
        }
        return $this->isSuperAdmin($user) || $user->can('delete roles');
    }
}