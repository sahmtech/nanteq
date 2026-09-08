<?php

namespace App\Filament\Auth;

use App\Models\Plan;
use App\Models\User;
use App\Services\SpecialistRegistrationService;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class RegisterSpecialist extends BaseRegister
{
    protected static string $layout = 'filament.layouts.auth-split';

    protected static string $view = 'filament.pages.auth.register-specialist';

    protected bool $hasTopbar = false;

    public function getLayout(): string
    {
        return 'filament.layouts.auth-split';
    }

    public function getExtraBodyAttributes(): array
    {
        return [
            'class' => 'auth-split-page',
        ];
    }

    public function hasLogo(): bool
    {
        return false;
    }

    public int $step = 1;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $phone_number = '';

    public ?int $plan_id = null;

    public ?string $specialist_code = null;

    public function mount(): void
    {
        if (Filament::auth()->check() && ! session('specialist_just_registered')) {
            $user = Filament::auth()->user();

            if ($user instanceof User && $user->isSpecialist()) {
                redirect()->intended(url('/specialist/sounds-progress'));

                return;
            }

            redirect()->intended(Filament::getUrl());

            return;
        }

        if (session('specialist_just_registered') && Filament::auth()->check()) {
            $this->step = 3;
            $this->specialist_code = Filament::auth()->user()->specialist_code;
        }
    }

    public function getTitle(): string | Htmlable
    {
        return __('dashboard.specialist_register_title');
    }

    public function getHeading(): string | Htmlable
    {
        return __('dashboard.specialist_register_heading');
    }

    public function plans()
    {
        $query = Plan::query();

        if (Plan::where('is_for_specialists', true)->exists()) {
            $query->where('is_for_specialists', true);
        }

        return $query->orderBy('price')->get();
    }

    public function recommendedPlanId(): ?int
    {
        return $this->plans()->sortByDesc('price')->first()?->id;
    }

    public function planPeriodLabel($plan): string
    {
        return match ($plan->periodicity_type) {
            'year' => __('dashboard.plan_per_year'),
            'day' => __('dashboard.plan_per_day'),
            default => __('dashboard.plan_per_month'),
        };
    }

    public function planFeatures($plan): array
    {
        $patientCount = (int) ($plan->patiant_count ?? 0);

        $patients = $patientCount > 0
            ? __('dashboard.plan_feature_patients', ['count' => $patientCount])
            : __('dashboard.plan_feature_unlimited_patients');

        return array_values(array_filter([
            $patients,
            __('dashboard.plan_feature_sounds_progress'),
            __('dashboard.plan_feature_stages_progress'),
        ]));
    }

    public function goToStepTwo(): void
    {
        $this->validate($this->stepOneRules());
        $this->step = 2;
    }

    public function goToStepOne(): void
    {
        $this->step = 1;
    }

    public function register(): ?RegistrationResponse
    {
        $this->validate(array_merge($this->stepOneRules(), [
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
        ]));

        $user = DB::transaction(function () {
            return app(SpecialistRegistrationService::class)->register([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'phone_number' => $this->phone_number,
                'plan_id' => $this->plan_id,
            ]);
        });

        Filament::auth()->login($user);
        session()->regenerate();
        session(['specialist_just_registered' => true]);

        $this->specialist_code = $user->specialist_code;
        $this->step = 3;

        return null;
    }

    public function goToDashboard()
    {
        session()->forget('specialist_just_registered');

        return redirect()->to(url('/specialist/sounds-progress'));
    }

    protected function stepOneRules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone_number' => ['required', 'numeric', 'unique:users,phone_number', 'regex:/^[\+0-9]{9,13}$/'],
        ];
    }

    /**
     * @return array<int | string, string | \Filament\Forms\Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->schema([])
                ->statePath('data'),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }
}
