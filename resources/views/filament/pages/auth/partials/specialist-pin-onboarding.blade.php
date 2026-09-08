@props([
    'pin' => '',
    'title' => null,
    'subtitle' => null,
    'pinLabel' => null,
    'notice' => null,
    'copyLabel' => null,
    'copiedLabel' => null,
    'ctaLabel' => null,
    'ctaAction' => 'goToDashboard',
])

@php
    $title ??= __('dashboard.specialist_code_intro');
    $subtitle ??= __('dashboard.specialist_pin_subtitle');
    $pinLabel ??= __('dashboard.specialist_pin_label');
    $notice ??= __('dashboard.specialist_pin_notice');
    $copyLabel ??= __('dashboard.copy_code');
    $copiedLabel ??= __('dashboard.copied');
    $ctaLabel ??= __('dashboard.go_to_dashboard');
@endphp

<div class="sp-onboard" x-data="{ copied: false }">
    <div class="sp-success-icon mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#0083a0]/10 p-4">
        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="#0083a0" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
        </svg>
    </div>

    <h2 class="mb-1 text-center text-2xl font-extrabold text-gray-900">{{ $title }}</h2>
    <p class="text-center text-sm text-gray-500">{{ $subtitle }}</p>

    <div class="sp-pin-box relative my-6 rounded-2xl border border-slate-200/80 bg-slate-50 p-6 text-center shadow-inner">
        <span class="mb-2 block text-xs font-bold uppercase tracking-wider text-[#0083a0]">{{ $pinLabel }}</span>
        <p class="my-3 select-all font-mono text-4xl font-black tracking-[0.3em] text-slate-800">{{ $pin }}</p>
        <button
            type="button"
            class="sp-copy-btn inline-flex items-center gap-2 rounded-xl border border-[#0083a0]/30 bg-white px-5 py-2 text-sm font-semibold text-[#0083a0] shadow-sm transition hover:bg-slate-100"
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

    <div class="mb-6 flex items-start gap-3 rounded-xl border border-blue-100 bg-blue-50/60 p-4 text-right text-xs leading-relaxed text-blue-900">
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#0083a0]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
        </svg>
        <p class="m-0">{{ $notice }}</p>
    </div>

    <button type="button" wire:click="{{ $ctaAction }}" class="aw-btn sp-cta">
        <span>{{ $ctaLabel }}</span>
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
    </button>
</div>
