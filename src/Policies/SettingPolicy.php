<?php

namespace Islamikit\Starterkit\Policies;

use App\Models\User;
use Islamikit\Starterkit\Traits\HandlesSuperAdmin;

class SettingPolicy
{
    use HandlesSuperAdmin;

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('view settings');
    }

    public function update(User $user): bool
    {
        return $this->isSuperAdmin($user) || $user->can('edit settings');
    }
}