@extends('layouts.app')

@section('content')

    <h1>Pagamento do pedido #{{ $order->id }}</h1>
    <p>Total: R$ {{ number_format((float) $order->total, 2, ',', '.') }}</p>

    <form action="{{ route('payment.store', $order) }}" method="POST">
        @csrf
        <button type="submit">Pagar com Mercado Pago</button>
    </form>

    @if($errors->any())
        <div role="alert">
            <p>{{ $errors->first() }}</p>
        </div>
    @endif