<?php

namespace App\Http\Requests\Auth;

use App\Rules\codeValidate;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class MobileVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => ['required', 'string', $this->isVerified(), new codeValidate($this->user())],
        ];
    }

    public function isVerified()
    {
        return function ($attribute, $value, $fail) {
            if ($this->user()->hasVerifiedMobile()) {
                $fail(__('auth.your phone number is already verified'));
            }
        };
    }

    /**
     * Fulfill the email verification request.
     *
     * @return void
     */
    public function fulfill()
    {
        if (! $this->user()->hasVerifiedMobile()) {
            $this->user()->markMobileAsVerified();

            event(new Verified($this->user()));
        }
    }

    /**
         * Configure the validator instance.
         *
         * @param  \Illuminate\Validation\Validator  $validator
         * @return void
         */
    public function withValidator(Validator $validator)
    {
        return $validator;
    }
}
