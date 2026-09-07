<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
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
            'name' => ['required', 'string', 'min:3'],
            'email' => ['string', 'email', 'max:255', Rule::unique('users'), 'nullable'],
            'phone_number' => ['required', 'numeric', 'unique:users,phone_number', 'regex:/^[\+0-9]{9,13}$/'],
            'role' => ['required', 'in:personal, parent, specialist'],
            'date_of_birth' => ['required_if:role,personal', 'date', 'date_format:Y-m-d'],
            'gender' => ['required', 'in:male,female'],
            'password' => ['required', 'confirmed', Password::defaults()]
        ];
    }
}
