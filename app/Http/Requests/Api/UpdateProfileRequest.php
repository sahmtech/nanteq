<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\Concerns\ValidatesSpecialistFollow;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateProfileRequest extends FormRequest
{
    use ValidatesSpecialistFollow;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareSpecialistFollow();
    }

    public function rules(): array
    {
        return array_merge([
            'age' => ['sometimes', 'integer', 'exists:ages,id'],
            'profile_picture' => ['nullable', 'image'],
            'gender' => ['sometimes', 'in:male,female'],
            'name' => ['sometimes', 'string'],
        ], $this->specialistFollowRules());
    }

    public function attributes(): array
    {
        return $this->specialistFollowAttributes();
    }

    public function messages(): array
    {
        return $this->specialistFollowMessages();
    }

    public function withValidator(Validator $validator): void
    {
        $this->withSpecialistFollowValidator($validator);
    }
}
