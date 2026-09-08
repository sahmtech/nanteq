<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->isSpecialist()) {
            return $panel->getId() === 'specialist';
        }

        return $panel->getId() === 'admin';
    }

    public function isSpecialist(): bool
    {
        return $this->hasRole('specialist');
    }

    public function plan()
    {
        return $this->hasOneThrough(Plan::class, Subscription::class);
    }

    public function assignedPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function followedSpecialist(): BelongsTo
    {
        return $this->belongsTo(self::class, 'followed_specialist_id');
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'followed_specialist_id')
            ->where('id', '!=', $this->getKey());
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'user_id');
    }

    public function ageGroup()
    {
        return $this->belongsTo(AgeGroup::class);
    }

    public function soundProgresses()
    {
        return $this->hasMany(SoundProgress::class, 'trainee_id');
    }

    public function levelProgresses()
    {
        return $this->hasMany(LevelProgress::class, 'trainee_id');
    }

    public function lastProgress()
    {
        return $this->hasOne(LevelProgress::class, 'trainee_id')->latest();
    }
    

    public function levels()
{
    return $this->belongsToMany(Level::class);
}

    /**
     * Check if the user has a specific permission.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if ($this->role->name === 'owner') {
            return true;
        }
        $permission = $this->role->permissions()->where('name', $permission)->first();
        if ($permission)
            return true;
        else
            return false;
    }
}
