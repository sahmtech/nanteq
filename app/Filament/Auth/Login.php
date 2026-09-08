<?php

namespace App\Filament\Auth;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    protected static string $layout = 'filament.layouts.auth-split';

    protected static string $view = 'filament.pages.auth.login';

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

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            $user = Filament::auth()->user();

            if ($user instanceof User && $user->isSpecialist()) {
                redirect()->intended(url('/specialist/sounds-progress'));

                return;
            }

            redirect()->intended(Filament::getUrl());

            return;
        }

        $this->form->fill();
    }

    public function getTitle(): string | Htmlable
    {
        return __('dashboard.login_title');
    }

    public function getHeading(): string | Htmlable
    {
        return __('dashboard.login_heading');
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (\DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException $exception) {
            \Filament\Notifications\Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        /** @var User $user */
        $user = Filament::auth()->user();

        if ($user->isSpecialist()) {
            session()->regenerate();

            return app(LoginResponse::class);
        }

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();
            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
