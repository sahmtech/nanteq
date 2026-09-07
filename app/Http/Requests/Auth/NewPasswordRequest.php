<?php

namespace App\Http\Requests\Auth;

use App\Rules\codeValidate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class NewPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'phone_number' => ['required', 'numeric', 'unique:users,phone_number', 'regex:/^[\+0-9]{9,13}$/'],
            'code' => ['required', 'string', new codeValidate($this)],
            'password' => ['required', 'confirmed', Password::defaults()]
        ];
    }
}
