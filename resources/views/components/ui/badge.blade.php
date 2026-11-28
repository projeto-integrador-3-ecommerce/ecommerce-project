@props(['variant' => 'navy'])

@php
    $variants = [
        'navy' => 'bg-navy text-white',
        'red' => 'bg-red text-white',
        'teal' => 'bg-teal text-white',
        'blue' => 'bg-blue text-white',
        'muted' => 'bg-muted/20 text-navy',
        'white' => 'bg-white/15 text-white ring-1 ring-white/40',
    ];
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex min-h-6 items-center justify-center gap-1 rounded-full px-3 text-[11px] font-semibold '.($variants[$variant] ?? $variants['navy']),
]) }}>
    {{ $slot }}
</span>
