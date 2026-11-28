@props(['label' => null, 'hint' => null])

<label class="block">
    @if ($label)
        <span class="mb-2 block text-xs font-semibold text-navy">{{ $label }}</span>
    @endif
    <span class="relative block">
        <select {{ $attributes->merge([
            'class' => 'min-h-12 w-full appearance-none rounded-full border border-navy/30 bg-white px-4 py-3 pr-10 text-sm text-navy focus:border-blue focus:outline-none focus-visible:ring-2 focus-visible:ring-blue/40 disabled:cursor-not-allowed disabled:bg-bg disabled:opacity-60',
        ]) }}>
            {{ $slot }}
        </select>
        <svg class="pointer-events-none absolute top-1/2 right-4 size-4 -translate-y-1/2 text-navy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
    </span>
    @if ($hint)
        <span class="mt-2 block text-xs text-muted">{{ $hint }}</span>
    @endif
</label>
