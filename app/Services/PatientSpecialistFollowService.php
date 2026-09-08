<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PatientSpecialistFollowService
{
    public function applyFromRequest(User $patient, Request $request): void
    {
        if ($patient->isSpecialist()) {
            return;
        }

        if (! $request->exists('follow_with_specialist') && ! $request->filled('specialist_pin')) {
            return;
        }

        if ($request->exists('follow_with_specialist') && ! $request->boolean('follow_with_specialist')) {
            $patient->forceFill(['followed_specialist_id' => null])->save();

            return;
        }

        $this->linkByPin($patient, (string) $request->input('specialist_pin'));
    }

    public function findActiveSpecialistByPin(string $pin): ?User
    {
        $pin = preg_replace('/\D/', '', $pin) ?? '';

        if ($pin === '') {
            return null;
        }

        return User::query()
            ->role('specialist')
            ->where('specialist_code', $pin)
            ->first();
    }

    public function linkByPin(User $patient, string $pin): void
    {
        $specialist = $this->findActiveSpecialistByPin($pin);

        if (! $specialist) {
            throw ValidationException::withMessages([
                'specialist_pin' => [__('api.specialist_pin_invalid')],
            ]);
        }

        if ((int) $patient->followed_specialist_id !== (int) $specialist->id) {
            $this->assertSpecialistHasCapacity($specialist);
        }

        $patient->forceFill(['followed_specialist_id' => $specialist->id])->save();
    }

    public function assertSpecialistHasCapacity(User $specialist): void
    {
        $limit = (int) ($specialist->assignedPlan?->patiant_count ?? 0);

        if ($limit <= 0) {
            return;
        }

        $current = $specialist->patients()->count();

        if ($current >= $limit) {
            throw ValidationException::withMessages([
                'specialist_pin' => [__('api.specialist_pin_full')],
            ]);
        }
    }
}
