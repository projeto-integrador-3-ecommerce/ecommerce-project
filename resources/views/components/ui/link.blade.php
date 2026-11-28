@props(['variant' => 'primary', 'size' => 'md'])

@php
    $variants = [
        'primary' => 'bg-navy text-white hover:bg-navy/90',
        'outline' => 'border border-navy bg-transparent text-navy hover:bg-navy/5',
        'light' => 'border border-white bg-transparent text-white hover:bg-white/10',
        'ghost' => 'text-navy hover:bg-aqua/40',
    ];
    $sizes = [
        'sm' => 'min-h-11 px-4 text-xs',
        'md' => 'min-h-12 px-6 text-sm',
        'lg' => 'min-h-14 px-8 text-sm',
    ];
@endphp

<a {{ $attributes->merge([
    'class' => 'inline-flex items-center justify-center gap-2 rounded-full font-semibold transition duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue focus-visible:ring-offset-2 focus-visible:ring-offset-bg aria-[current=page]:bg-blue aria-[current=page]:text-white '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']),
]) }}>
    {{ $slot }}
</a>
