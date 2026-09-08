<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        if (auth()->user()?->isSpecialist()) {
            return redirect()->intended(url('/specialist/sounds-progress'));
        }

        return redirect()->intended(Filament::getUrl());
    }
}
