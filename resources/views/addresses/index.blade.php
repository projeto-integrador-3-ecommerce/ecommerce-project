@extends('layouts.storefront', ['title' => auth()->user()->is_admin ? 'Endereços' : 'Meus endereços'])

@section('content')

    <section class="page-head"><div class="container"><span class="eyebrow">Sua conta</span><h1>{{ auth()->user()->is_admin ? 'Endereços cadastrados' : 'Meus endereços' }}</h1><p>Gerencie seus endereços de entrega.</p></div></section>
    <section class="container pb-20">
        @unless (auth()->user()->is_admin)<p class="mb-6"><a class="btn btn--primary" href="{{ route('addresses.create') }}">Cadastrar endereço</a></p>@endunless
        <div class="info-grid">
            @forelse ($addresses as $address)
                <article class="info-card">
                    <strong>{{ $address->street }}, {{ $address->number }}</strong>
                    <p>{{ $address->neighborhood }}, {{ $address->city }} - {{ $address->state }} · CEP {{ $address->cep }}</p>
                    @if (auth()->user()->is_admin)
                        <p class="mt-3">{{ $address->user->name }} ({{ $address->user->email }})</p>
                    @else
                        <div class="btn-row mt-4"><a class="btn btn--outline" href="{{ route('addresses.edit', $address) }}">Editar</a>
                            <form action="{{ route('addresses.destroy', $address) }}" method="POST">@csrf @method('DELETE')<button class="btn btn--ghost" type="submit">Remover</button></form>
                        </div>
                    @endif
                </article>
            @empty
                <div class="empty-state"><h2>Nenhum endereço cadastrado</h2><p>Cadastre um endereço para usar nas suas compras.</p><a class="btn btn--primary" href="{{ route('addresses.create') }}">Cadastrar endereço</a></div>
            @endforelse
        </div>
        <div class="mt-6">{{ $addresses->links() }}</div>
    </section>

@endsection
