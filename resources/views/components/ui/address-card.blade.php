@props(['address' => null, 'title' => 'Endereço de envio', 'editRoute' => null, 'editLabel' => 'Alterar endereço'])

<article {{ $attributes->merge([
    'class' => 'rounded-3xl border border-aqua bg-white p-5 shadow-sm sm:p-6',
]) }}>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-base font-semibold text-navy">{{ $title }}</h2>
        @if ($editRoute)
            <a href="{{ $editRoute }}" class="inline-flex min-h-11 items-center gap-1 rounded-full px-3 text-xs font-semibold text-navy transition hover:bg-aqua/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
                {{ $editLabel }}
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        @endif
    </div>
    @if ($address)
        <div class="mt-4 grid gap-4 border-t border-aqua pt-4 sm:grid-cols-2">
            <div>
                <span class="block text-xs text-muted">Nome</span>
                <span class="block text-sm font-semibold text-navy">{{ $address->user?->name ?? 'Cliente Dimensiona' }}</span>
                <span class="mt-2 block text-xs text-muted">Telefone</span>
                <span class="block text-sm text-navy">{{ $address->user?->telephone ?? 'Não informado' }}</span>
            </div>
            <div>
                <span class="block text-xs text-muted">Rua e número</span>
                <span class="block text-sm font-semibold text-navy">{{ $address->street }}, {{ $address->number }}@if ($address->complement), {{ $address->complement }}@endif</span>
                <span class="mt-2 block text-xs text-muted">Bairro / CEP</span>
                <span class="block text-sm text-navy">{{ $address->neighborhood }} · CEP {{ $address->cep }}</span>
                <span class="mt-2 block text-xs text-muted">Cidade / Estado</span>
                <span class="block text-sm text-navy">{{ $address->city }} - {{ $address->state }}</span>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs text-muted">
            <svg class="size-4 text-navy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20s7-3.5 7-9V5l-7-2-7 2v6c0 5.5 7 9 7 9Z"/><path d="m9 12 2 2 4-4"/></svg>
            Endereço verificado para entrega
        </div>
    @else
        <p class="mt-4 border-t border-aqua pt-4 text-sm text-muted">Nenhum endereço selecionado.</p>
    @endif
</article>
