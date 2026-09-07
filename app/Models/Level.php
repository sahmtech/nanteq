<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    public function letterProgress()
    {
        return $this->hasOne(LevelProgress::class)->where('trainee_id', auth()->user()->id);
    }

    public function previousLevel()
    {
        return $this->belongsTo(Level::class, 'previous_level_id');
    }

    public function sounds()
    {
        return $this->hasMany(Sound::class);
    }
}
