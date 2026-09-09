@props([
    'pin' => '',
    'title' => null,
    'subtitle' => null,
    'pinLabel' => null,
    'notice' => null,
    'copyLabel' => null,
    'copiedLabel' => null,
    'ctaLabel' => null,
    'ctaUrl' => null,
])

@php
    $title ??= __('dashboard.specialist_code_intro');
    $subtitle ??= __('dashboard.specialist_pin_subtitle');
    $pinLabel ??= __('dashboard.specialist_pin_label');
    $notice ??= __('dashboard.specialist_pin_notice');
    $copyLabel ??= __('dashboard.copy_code');
    $copiedLabel ??= __('dashboard.copied');
    $ctaLabel ??= __('dashboard.go_to_dashboard');
    $ctaUrl ??= url('/specialist/sounds-progress');
@endphp

<div class="sp-onboard" x-data="{ copied: false }">
    <div class="sp-success-icon">
        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="#0083a0" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
        </svg>
    </div>

    <h2 class="sp-onboard-title">{{ $title }}</h2>
    <p class="sp-onboard-subtitle">{{ $subtitle }}</p>

    <div class="sp-pin-box">
        <span class="sp-pin-label">{{ $pinLabel }}</span>
        <p class="sp-pin-value" dir="ltr">{{ $pin }}</p>
        <button
            type="button"
            class="sp-copy-btn"
            @click="navigator.clipboard.writeText(@js($pin)); copied = true; setTimeout(() => copied = false, 1800)"
        >
            <svg x-show="!copied" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <rect x="9" y="9" width="13" height="13" rx="2"></rect>
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
            </svg>
            <svg x-show="copied" x-cloak class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
            <span x-show="!copied">{{ $copyLabel }}</span>
            <span x-show="copied" x-cloak>{{ $copiedLabel }}!</span>
        </button>
        <div
            x-show="copied"
            x-cloak
            x-transition
            class="sp-toast"
        >
            {{ $copiedLabel }}!
        </div>
    </div>

    <div class="sp-notice">
        <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="#0083a0" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
        </svg>
        <p>{{ $notice }}</p>
    </div>

    <a href="{{ $ctaUrl }}" class="aw-btn sp-cta">
        <span>{{ $ctaLabel }}</span>
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
    </a>
</div>
