<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Letter extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function levels()
    {
        return $this->hasMany(Level::class);
    }

    public function ageGroup()
    {
        return $this->belongsTo(AgeGroup::class);
    }

    public function LevelsProgresses()
    {
        return $this->hasMany(LevelProgress::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
