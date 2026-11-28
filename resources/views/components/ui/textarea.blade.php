@props(['label' => null, 'hint' => null])

<label class="block">
    @if ($label)
        <span class="mb-2 block text-xs font-semibold text-navy">{{ $label }}</span>
    @endif
    <textarea {{ $attributes->merge([
        'rows' => 4,
        'class' => 'w-full rounded-3xl border border-navy/30 bg-white px-4 py-3 text-sm text-navy placeholder:text-muted focus:border-blue focus:outline-none focus-visible:ring-2 focus-visible:ring-blue/40 disabled:cursor-not-allowed disabled:bg-bg disabled:opacity-60',
    ]) }}>{{ $slot }}</textarea>
    @if ($hint)
        <span class="mt-2 block text-xs text-muted">{{ $hint }}</span>
    @endif
</label>
