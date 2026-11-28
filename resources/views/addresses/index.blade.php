@extends('layouts.storefront', ['title' => auth()->user()->is_admin ? 'Endereços' : 'Meus endereços'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Sua conta</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">{{ auth()->user()->is_admin ? 'Endereços cadastrados' : 'Meus endereços' }}</h1>
    <p class="mt-3 text-sm text-muted">Gerencie seus endereços de entrega.</p>
    @unless (auth()->user()->is_admin)
        <x-ui.link href="{{ route('addresses.create') }}" size="sm" class="mt-6">Cadastrar endereço</x-ui.link>
    @endunless
</section>

<section class="mt-8">
    <div class="grid gap-4 md:grid-cols-2">
        @forelse ($addresses as $address)
            <article class="rounded-3xl border border-aqua bg-white p-5 shadow-sm transition hover:border-blue sm:p-6">
                <div class="flex items-start gap-3">
                    <span class="grid size-11 shrink-0 place-items-center rounded-full bg-mist text-navy">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s7-5.5 7-12a7 7 0 1 0-14 0c0 6.5 7 12 7 12Z"/><circle cx="12" cy="9" r="2.5"/></svg>
                    </span>
                    <div class="min-w-0">
                        <strong class="block text-sm font-semibold text-navy">{{ $address->street }}, {{ $address->number }}</strong>
                        <p class="mt-1 text-xs text-muted">{{ $address->neighborhood }}, {{ $address->city }} - {{ $address->state }} · CEP {{ $address->cep }}</p>
                        @if (auth()->user()->is_admin)
                            <p class="mt-3 text-xs text-muted">{{ $address->user->name }} ({{ $address->user->email }})</p>
                        @endif
                    </div>
                </div>
                @unless (auth()->user()->is_admin)
                    <div class="mt-5 flex flex-wrap gap-2 border-t border-aqua pt-4">
                        <x-ui.link href="{{ route('addresses.edit', $address) }}" variant="outline" size="sm">Editar</x-ui.link>
                        <form action="{{ route('addresses.destroy', $address) }}" method="POST">@csrf @method('DELETE')<x-ui.button type="submit" variant="danger" size="sm">Remover</x-ui.button></form>
                    </div>
                @endunless
            </article>
        @empty
            <div class="md:col-span-2 rounded-3xl border border-aqua bg-white p-8 text-center shadow-sm">
                <h2 class="text-xl font-semibold text-navy">Nenhum endereço cadastrado</h2>
                <p class="mt-2 text-sm text-muted">Cadastre um endereço para usar nas suas compras.</p>
                <x-ui.link href="{{ route('addresses.create') }}" class="mt-6">Cadastrar endereço</x-ui.link>
            </div>
        @endforelse
    </div>
    <div class="mt-6 flex flex-wrap gap-2 [&_a]:inline-flex [&_a]:min-h-11 [&_a]:items-center [&_a]:rounded-full [&_a]:border [&_a]:border-navy/20 [&_a]:px-3 [&_a]:text-xs [&_a]:font-semibold [&_a]:text-navy [&_a]:hover:bg-aqua/40 [&_span]:inline-flex [&_span]:min-h-11 [&_span]:items-center [&_span]:px-2 [&_span]:text-xs [&_span]:text-muted">{{ $addresses->links() }}</div>
</section>
@endsection
