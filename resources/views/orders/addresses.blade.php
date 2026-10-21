@extends('layouts.storefront', ['title' => 'Endereço de entrega'])

@section('content')

    <section class="page-head"><div class="container"><span class="eyebrow">Pedido #{{ $order->id }}</span><h1>Endereço de entrega</h1><p>Selecione onde deseja receber seu pedido.</p></div></section>
    <section class="container auth pb-20">
        <p class="mb-6"><a class="btn btn--outline" href="{{ route('addresses.create', ['order_id' => $order->id]) }}">Cadastrar novo endereço</a></p>
        @if ($addresses->isNotEmpty())
            <form action="{{ route('orders.address', $order) }}" method="POST">@csrf
                @foreach ($addresses as $address)
                    <label class="info-card block mb-3"><input type="radio" name="address_id" value="{{ $address->id }}" required>
                        <strong>{{ $address->street }}, {{ $address->number }}</strong>
                        <p>{{ $address->neighborhood }}, {{ $address->city }} - {{ $address->state }} · CEP {{ $address->cep }}</p>
                    </label>
                @endforeach
                <button class="btn btn--primary btn--block" type="submit">Usar este endereço</button>
            </form>
        @else
            <div class="empty-state"><h2>Nenhum endereço cadastrado</h2><p>Cadastre um endereço para continuar com o pedido.</p></div>
        @endif
        <a class="btn btn--ghost btn--block mt-4" href="{{ route('orders.show', $order) }}">Voltar ao pedido</a>
    </section>

@endsection
