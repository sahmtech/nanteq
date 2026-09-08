@props([
    'livewire' => null,
])

<x-filament-panels::layout.base :livewire="$livewire">
    <link rel="stylesheet" href="{{ asset('css/auth-wide.css') }}?v=7">
    {{ $slot }}
</x-filament-panels::layout.base>
