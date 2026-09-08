@php
    $pin = $pin ?? auth()->user()?->specialist_code;
@endphp

@if (filled($pin))
    <div class="spb">
        <p class="spb-label">{{ __('dashboard.specialist_pin_short_label') }}</p>
        <div class="spb-row">
            <span class="spb-pin" dir="ltr">{{ $pin }}</span>
            <button type="button" wire:click="copyPin" class="spb-btn" title="{{ __('dashboard.copy_code') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <rect x="9" y="9" width="13" height="13" rx="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>{{ __('dashboard.copy_code') }}</span>
            </button>
        </div>
    </div>
@endif
