@extends('layouts.storefront', ['title' => 'Endereço de entrega'])

@section('content')

    <section><div><span>Pedido #{{ $order->id }}</span><h1>Endereço de entrega</h1><p>Selecione onde deseja receber seu pedido.</p></div></section>
    <section>
        <p><a href="{{ route('addresses.create', ['order_id' => $order->id]) }}">Cadastrar novo endereço</a></p>
        @if ($addresses->isNotEmpty())
            <form action="{{ route('orders.address', $order) }}" method="POST">@csrf
                @foreach ($addresses as $address)
                    <label><input type="radio" name="address_id" value="{{ $address->id }}" required>
                        <strong>{{ $address->street }}, {{ $address->number }}</strong>
                        <p>{{ $address->neighborhood }}, {{ $address->city }} - {{ $address->state }} · CEP {{ $address->cep }}</p>
                    </label>
                @endforeach
                <button type="submit">Usar este endereço</button>
            </form>
        @else
            <div><h2>Nenhum endereço cadastrado</h2><p>Cadastre um endereço para continuar com o pedido.</p></div>
        @endif
        <a href="{{ route('orders.show', $order) }}">Voltar ao pedido</a>
    </section>

@endsection
