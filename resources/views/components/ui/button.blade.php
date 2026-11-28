@props(['variant' => 'primary', 'size' => 'md'])

@php
    $variants = [
        'primary' => 'bg-navy text-white hover:bg-navy/90 active:scale-[0.98]',
        'outline' => 'border border-navy bg-transparent text-navy hover:bg-navy/5 active:scale-[0.98]',
        'light' => 'bg-white text-navy hover:bg-mist/50 active:scale-[0.98]',
        'ghost' => 'text-navy hover:bg-aqua/40 active:scale-[0.98]',
        'danger' => 'border border-red bg-white text-red hover:bg-red/5 active:scale-[0.98]',
    ];
    $sizes = [
        'sm' => 'min-h-11 px-4 text-xs',
        'md' => 'min-h-12 px-6 text-sm',
        'lg' => 'min-h-14 px-8 text-sm',
    ];
@endphp

<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center justify-center gap-2 rounded-full font-semibold transition duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue focus-visible:ring-offset-2 focus-visible:ring-offset-bg disabled:cursor-not-allowed disabled:opacity-50 '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']),
]) }}>
    {{ $slot }}
</button>
