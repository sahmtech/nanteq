<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Cache;

class codeValidate implements ValidationRule
{

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure( string ): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $code =  $this->user->code;
        
        if (!$code || $code != $value) {
            $fail(__('auth.the_code_you_entered_is_expired_or_incorrect'));
            return;
        }
    }
}
