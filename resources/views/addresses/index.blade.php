@extends('layouts.storefront', ['title' => auth()->user()->is_admin ? 'Endereços' : 'Meus endereços'])

@section('content')

    <section><div><span>Sua conta</span><h1>{{ auth()->user()->is_admin ? 'Endereços cadastrados' : 'Meus endereços' }}</h1><p>Gerencie seus endereços de entrega.</p></div></section>
    <section>
        @unless (auth()->user()->is_admin)<p><a href="{{ route('addresses.create') }}">Cadastrar endereço</a></p>@endunless
        <div>
            @forelse ($addresses as $address)
                <article>
                    <strong>{{ $address->street }}, {{ $address->number }}</strong>
                    <p>{{ $address->neighborhood }}, {{ $address->city }} - {{ $address->state }} · CEP {{ $address->cep }}</p>
                    @if (auth()->user()->is_admin)
                        <p>{{ $address->user->name }} ({{ $address->user->email }})</p>
                    @else
                        <div><a href="{{ route('addresses.edit', $address) }}">Editar</a>
                            <form action="{{ route('addresses.destroy', $address) }}" method="POST">@csrf @method('DELETE')<button type="submit">Remover</button></form>
                        </div>
                    @endif
                </article>
            @empty
                <div><h2>Nenhum endereço cadastrado</h2><p>Cadastre um endereço para usar nas suas compras.</p><a href="{{ route('addresses.create') }}">Cadastrar endereço</a></div>
            @endforelse
        </div>
        <div>{{ $addresses->links() }}</div>
    </section>

@endsection
