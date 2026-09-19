@extends('layouts.app')

@section('content')

    <h1>Finalizar Compra: Id do pedido #{{ $order->id }}</h1>
    <p>Status: {{ $order->status }}</p>
    <p>Total: R${{ $order->total }}</p>

    <form action="">
        @csrf
        <button type="submit">Adicionar Endereço</button>
    </form>
    <form action="{{ route('orders.destroy', $order)}}" method="POST">
        @csrf
        @method("DELETE")
        <button type="submit">Cancelar compra</button>
    </form>

@endsection
