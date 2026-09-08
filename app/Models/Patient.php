<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Patient extends User
{
    protected $table = 'users';

    public function scopeFollowingSpecialist(Builder $query, int $specialistId): Builder
    {
        return $query->where('followed_specialist_id', $specialistId);
    }
}
