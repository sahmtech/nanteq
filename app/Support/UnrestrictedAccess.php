<?php

namespace App\Support;

use App\Models\User;

class UnrestrictedAccess
{
    public static function allows(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        $allowed = collect(config('access.unrestricted_phones', []))
            ->map(fn ($phone) => self::normalize((string) $phone))
            ->filter()
            ->all();

        if ($allowed === []) {
            return false;
        }

        foreach (self::userNumbers($user) as $number) {
            if (in_array($number, $allowed, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<int, string>
     */
    protected static function userNumbers(User $user): array
    {
        $code = self::digits((string) $user->phone_code);
        $number = self::digits((string) $user->phone_number);

        return array_values(array_unique(array_filter([
            self::normalize($code.$number),
            self::normalize($number),
        ])));
    }

    public static function normalize(string $phone): string
    {
        $digits = self::digits($phone);

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (! str_starts_with($digits, '966')) {
            $digits = '966'.$digits;
        }

        return $digits;
    }

    protected static function digits(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?? '';
    }
}
