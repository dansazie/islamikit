<?php

namespace Islamikit\Starterkit\Policies;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Islamikit\Starterkit\Traits\HandlesSuperAdmin;

class PermissionPolicy
{
    use HandlesSuperAdmin;

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('view permissions');
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('create permissions');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $this->isSuperAdmin($user) || $user->can('edit permissions');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $this->isSuperAdmin($user) || $user->can('delete permissions');
    }
}