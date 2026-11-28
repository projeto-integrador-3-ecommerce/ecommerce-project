@props(['label' => null, 'hint' => null])

<label class="block">
    @if ($label)
        <span class="mb-2 block text-xs font-semibold text-navy">{{ $label }}</span>
    @endif
    <input {{ $attributes->merge([
        'type' => 'text',
        'class' => 'min-h-12 w-full rounded-full border border-navy/30 bg-white px-4 py-3 text-sm text-navy placeholder:text-muted focus:border-blue focus:outline-none focus-visible:ring-2 focus-visible:ring-blue/40 disabled:cursor-not-allowed disabled:bg-bg disabled:opacity-60',
    ]) }}>
    @if ($hint)
        <span class="mt-2 block text-xs text-muted">{{ $hint }}</span>
    @endif
</label>
