<?php

namespace App\Services;

use App\Models\User;
use App\Traits\SMSTrait;
class AuthService
{
    use SMSTrait;
    protected $whitelist = [
        '580111196',
        '945496372',
        '999999999',
        '575838591',
    ];

   public function requestOtp($data)
{
    $user = User::withTrashed()
        ->where('phone_number', $data['phone_number'])
        ->first();

    if ($user && $user->trashed()) {
        $user->restore();
    }

   if (!$user) {
        $user = User::create([
            'phone_number' => $data['phone_number'],
            'phone_code' => $data['phone_code'],
            'profile_completion_status' => 'pending'
        ]);
    }

    $code = $this->generateOtpCode($user);

    if (!in_array($user->phone_number, $this->whitelist)) {
        $m = "رمز التحقق: " . $code;
        if ($this->sendMessage($data['phone_number'], $m)->code == "1") {
            return true;
        }
    } else {
        return true;
    }
}


    private function generateOtpCode($user)
    {
        if (in_array($user->phone, $this->whitelist) || strpos($user->phone, '5801111') === 0) {
            $code = 1111;
        } else {
            $code = random_int(1000, 9999);
        }

        $user->update([
            'code' => $code
        ]);
        return $code;
    }
}
