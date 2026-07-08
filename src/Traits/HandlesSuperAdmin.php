<?php

namespace Islamikit\Starterkit\Traits;

use App\Models\User;

trait HandlesSuperAdmin
{
    protected function isSuperAdmin(User $user): bool
    {
        return $user->hasRole('super-admin');
    }
}