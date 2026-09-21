@extends('layouts.app')

@section('content')

    <h1>Finalizar Compra: Id do pedido #{{ $order->id }}</h1>
    <p>Status: {{ $order->status }}</p>
    <p>Total: R${{ $order->total }}</p>

    @if($order->address)
        <h2>Endereço de entrega:</h2>
        <p>
            {{ $order->address->street }},
            {{ $order->address->number }}
            @if($order->address->complement)
                - {{ $order->address->complement }}
            @endif
            <br>
            {{ $order->address->neighborhood }},
            {{ $order->address->city }} - {{ $order->address->state }}
            <br>
            CEP: {{ $order->address->cep }}
        </p>

        <button type="button">adicionar pagamento</button>
    @else
        <a href="{{ route('orders.addresses', $order) }}">Adicionar endereço</a>
    @endif

    <form action="{{ route('orders.destroy', $order)}}" method="POST">
        @csrf
        @method("DELETE")
        <button type="submit">Cancelar compra</button>
    </form>

@endsection
