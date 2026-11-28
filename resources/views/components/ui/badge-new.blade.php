@props(['label' => 'Novo'])

<span {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center gap-1 rounded-full bg-[#ff0000] px-2.5 py-1 text-[11px] font-bold leading-none text-white shadow-sm']) }}>
    <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 15 6-6 6 6"/></svg>
    <span class="sr-only">{{ $label }}</span>
</span>
