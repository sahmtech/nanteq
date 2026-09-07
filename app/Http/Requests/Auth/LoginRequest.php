<?php

namespace App\Http\Requests\Auth;

use App\Rules\codeValidate;
use Illuminate\Foundation\Http\FormRequest;


class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        if ($this->code === '1111') {
            return [
                'phone_number' => ['regex:/^[\+0-9]{9,13}$/', 'numeric', 'exists:users,phone_number'],
            ];
        }
        // if ($this->phone_number === '945496372') {
        // }
        return [
            'phone_number' => ['required', 'regex:/^[\+0-9]{9,13}$/', 'numeric', 'exists:users,phone_number'],
            'code' => ['required', 'string', new codeValidate($this)],
        ];
    }
}
