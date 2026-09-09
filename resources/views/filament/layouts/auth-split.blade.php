@props([
    'livewire' => null,
])

<x-filament-panels::layout.base :livewire="$livewire">
    <link rel="stylesheet" href="{{ asset('css/auth-wide.css') }}?v=10">
    <link rel="stylesheet" href="{{ asset('css/brand.css') }}?v=3">
    {{ $slot }}
</x-filament-panels::layout.base>
