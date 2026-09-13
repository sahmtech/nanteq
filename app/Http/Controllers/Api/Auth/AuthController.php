<?php

namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Traits\SMSTrait;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    use SMSTrait;

    public function __construct(private AuthService $authService) {
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();

        $token = $user->createToken($user->phone_number)->plainTextToken;

        $subscription = $user->subscription;
        if ($user->hasUnrestrictedAccess() && (! $subscription || $subscription->status !== 'active')) {
            $subscription = [
                'status' => 'active',
            ];
        }

        return $this->withSuccess([
            'profile_completion_status' => $user->hasUnrestrictedAccess() ? 'completed' : $user->profile_completion_status,
            'user_subscription' => $subscription,
            'has_subscription' => $user->hasUnrestrictedAccess() || ($user->subscription && $user->subscription->status === 'active'),
            'token' => $token
        ]);
    }

    public function otpRequest(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'regex:/^[\+0-9]{9,13}$/', 'numeric'],
            'phone_code' => ['required', 'regex:/^[\+0-9]{1,5}$/', 'numeric'],
        ]);
       
        $status = $this->authService->requestOtp($validated);

        if($status){
            return $this->withSuccess(message: __('api.otp_sent'));
        }

        return $this->withError(message: __('api.otp_not_sent'), code: 500);
    }


    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->withSuccess(message: __('api.logged_out'));
    }
}
