<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SpecialistRegistrationService
{
    public function register(array $data): User
    {
        $code = $this->generateUniqueCode();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone_number' => $data['phone_number'],
            'gender' => $data['gender'] ?? 'male',
            'plan_id' => $data['plan_id'],
            'specialist_code' => $code,
            'profile_completion_status' => 'completed',
        ]);

        $user->assignRole($this->ensureSpecialistRole());
        $this->createSubscription($user, (int) $data['plan_id']);

        return $user;
    }

    public function generateUniqueCode(): string
    {
        do {
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (User::where('specialist_code', $code)->exists());

        return $code;
    }

    public function ensureSpecialistRole(): Role
    {
        $role = Role::findOrCreate('specialist', 'web');

        $permissionNames = [
            'view_any_sound::progress',
            'view_sound::progress',
            'view_any_level::progress',
            'view_level::progress',
        ];

        foreach ($permissionNames as $name) {
            $permission = Permission::findOrCreate($name, 'web');
            if (! $role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }
        }

        return $role;
    }

    protected function createSubscription(User $user, int $planId): void
    {
        $plan = Plan::find($planId);

        if (! $plan || $user->subscription()->exists()) {
            return;
        }

        $endDate = Carbon::now();
        $period = (int) ($plan->period ?: 1);

        if ($plan->periodicity_type === 'month') {
            $endDate = $endDate->addMonths($period);
        } elseif ($plan->periodicity_type === 'year') {
            $endDate = $endDate->addYears($period);
        } else {
            $endDate = $endDate->addDays($period);
        }

        $user->subscription()->create([
            'plan_id' => $planId,
            'start_date' => Carbon::now(),
            'end_date' => $endDate,
            'status' => 'active',
        ]);
    }
}
