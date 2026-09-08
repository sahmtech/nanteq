<?php

namespace App\Livewire;

use Filament\Notifications\Notification;
use Illuminate\Support\Js;
use Illuminate\View\View;
use Livewire\Component;

class SpecialistPinBanner extends Component
{
    public function copyPin(): void
    {
        $pin = auth()->user()?->specialist_code;

        if (blank($pin)) {
            return;
        }

        $this->js('window.navigator.clipboard.writeText('.Js::from($pin).')');

        Notification::make()
            ->title(__('dashboard.copied'))
            ->body(__('dashboard.specialist_pin_copied_body'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.specialist-pin-banner', [
            'pin' => auth()->user()?->specialist_code,
        ]);
    }
}
