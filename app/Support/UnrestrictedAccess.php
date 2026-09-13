<?php

namespace App\Support;

use App\Models\User;

class UnrestrictedAccess
{
    public static function allows(?User $user): bool
    {
        return $user?->hasUnrestrictedAccess() ?? false;
    }
}
