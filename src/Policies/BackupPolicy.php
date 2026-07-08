<?php

namespace Islamikit\Starterkit\Policies;

use App\Models\User;
use Islamikit\Starterkit\Traits\HandlesSuperAdmin;

class BackupPolicy
{
    use HandlesSuperAdmin;

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('view backups');
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('create backups');
    }

    public function download(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('download backups');
    }

    public function delete(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('delete backups');
    }
}