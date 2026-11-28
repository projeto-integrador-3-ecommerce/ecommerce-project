@props(['size' => 'md'])

@php
    $sizes = [
        'sm' => 'size-9 p-1.5',
        'md' => 'size-12 p-2.5',
        'lg' => 'size-16 p-3.5',
    ];
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex shrink-0 items-center justify-center rounded-full bg-navy shadow-sm '.($sizes[$size] ?? $sizes['md']),
]) }}>
    <img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="28" height="28">
</span>
