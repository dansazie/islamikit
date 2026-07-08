<?php

namespace Islamikit\Starterkit\Policies;

use Islamikit\Starterkit\Models\Menu;
use App\Models\User;
use Islamikit\Starterkit\Traits\HandlesSuperAdmin;

class MenuPolicy
{
    use HandlesSuperAdmin;

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('view menus');
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('create menus');
    }

    public function update(User $user, Menu $menu): bool
    {
        return $this->isSuperAdmin($user) || $user->can('edit menus');
    }

    public function delete(User $user, Menu $menu): bool
    {
        return $this->isSuperAdmin($user) || $user->can('delete menus');
    }

    public function reorder(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('edit menus');
    }
}