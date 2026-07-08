<?php

namespace Islamikit\Starterkit\Policies;

use App\Models\User;
use Islamikit\Starterkit\Traits\HandlesSuperAdmin;

class UserPolicy
{
    use HandlesSuperAdmin;

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('view users');
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('create users');
    }

    public function delete(User $user, User $target): bool
    {
        // Tidak bisa hapus diri sendiri
        if ($user->id === $target->id) {
            return false;
        }
        return $this->isSuperAdmin($user) || $user->can('delete users');
    }

    public function impersonate(User $user, User $target): bool
    {
        // Tidak bisa impersonate diri sendiri
        if ($user->id === $target->id) {
            return false;
        }
        return $this->isSuperAdmin($user) || $user->can('impersonate users');
    }
}