<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelProgress extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $table = 'level_progresses';
    
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function previousLevel()
    {
        return $this->belongsTo(Level::class, 'previous_level_id');
    }

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    public function trainee()
    {
        return $this->belongsTo(User::class);
    }
}
