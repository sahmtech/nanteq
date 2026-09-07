<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoundProgress extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $table = 'ratings';

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'records' => 'array',
            'result' => 'array'
        ];
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    public function trainee()
    {
        return $this->belongsTo(User::class);
    }

    public function sound()
    {
        return $this->belongsTo(Sound::class);
    }
}
