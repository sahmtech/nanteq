<?php

namespace App\Http\Requests\Api\Concerns;

use App\Services\PatientSpecialistFollowService;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

trait ValidatesSpecialistFollow
{
    protected function specialistFollowRules(): array
    {
        return [
            'follow_with_specialist' => ['sometimes', 'boolean'],
            'specialist_pin' => [
                'nullable',
                'string',
                Rule::requiredIf(fn () => $this->boolean('follow_with_specialist')),
                'regex:/^\d{6}$/',
            ],
        ];
    }

    protected function prepareSpecialistFollow(): void
    {
        if ($this->exists('specialist_pin')) {
            $this->merge([
                'specialist_pin' => preg_replace('/\D/', '', (string) $this->input('specialist_pin')),
            ]);
        }
    }

    protected function withSpecialistFollowValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            if ($this->exists('follow_with_specialist') && ! $this->boolean('follow_with_specialist')) {
                return;
            }

            $pin = $this->input('specialist_pin');

            if (blank($pin)) {
                return;
            }

            $specialist = app(PatientSpecialistFollowService::class)->findActiveSpecialistByPin((string) $pin);

            if (! $specialist) {
                $validator->errors()->add('specialist_pin', __('api.specialist_pin_invalid'));
            }
        });
    }

    public function specialistFollowAttributes(): array
    {
        return [
            'follow_with_specialist' => __('api.follow_with_specialist'),
            'specialist_pin' => __('api.specialist_pin'),
        ];
    }

    public function specialistFollowMessages(): array
    {
        return [
            'specialist_pin.required' => __('api.specialist_pin_required'),
            'specialist_pin.regex' => __('api.specialist_pin_invalid'),
        ];
    }
}
