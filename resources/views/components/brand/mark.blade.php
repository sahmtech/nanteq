@props([
    'variant' => 'default',
])

<img
    src="{{ \App\Support\Brand::logo() }}"
    alt="نطق"
    {{ $attributes->class([
        'nq-brand-logo',
        'nq-brand-logo--auth' => $variant === 'auth',
        'nq-brand-logo--sidebar' => $variant === 'sidebar',
    ]) }}
>
