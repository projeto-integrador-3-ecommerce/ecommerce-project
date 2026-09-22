@extends('layouts.app')

@section('content')

    <h1>Compra realizada do pedido #{{ $order->id }}</h1>
    <p>Status: {{ $order->status }}</p>

    <p>
        Forma de pagamento:
        @if($order->payment)
            @switch($order->payment->method)
                @case('cartao')
                    Cartão de crédito/débito
                    @break
                @case('boleto')
                    Boleto
                    @break
                @case('pix')
                    PIX
                    @break
                @default
                    {{ $order->payment->method }}
            @endswitch
        @else
            Não informado
        @endif
    </p>

    <h2>Endereço de entrega:</h2>
    @if($order->address)
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
    @else
        <p>Não informado</p>
    @endif

    <p>Total do pedido: R${{ $order->total }}</p>

    @if($order->status === 'Realizado')
        <a href="{{ route('products.index') }}">Voltar às compras</a>
    @elseif($order->address)
        <a href="{{ route('payment.create', $order) }}">Adicionar Pagamento</a>
    @else
        <a href="{{ route('orders.addresses', $order) }}">Adicionar endereço</a>
    @endif

    <form action="{{ route('orders.destroy', $order)}}" method="POST">
        @csrf
        @method("DELETE")
        <button type="submit">Cancelar compra</button>
    </form>

@endsection
