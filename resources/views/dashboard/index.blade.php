@extends('layouts.app')

@section('content')

    <h1>{{ auth()->user()->is_admin ? 'Todos os pedidos' : 'Meus pedidos' }}</h1>
    <section id="orders">
        @foreach($orders as $order)
            <div>
                <h2>Pedido #{{ $order->id }}</h2>
                <p>Status: {{ $order->statusLabel() }}</p>
                @if(auth()->user()->is_admin)
                    <p>Cliente: {{ $order->user->name }}</p>
                @endif
                <p>Endereço do pedido: {{ $order->address->street ?? 'Endereço não encontrado' }}</p>
                <p>Criado em: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <a href="{{ route('orders.show', $order) }}">Ver pedido</a>
            </div>
        @endforeach
    </section>
    {{ $orders->links() }}

@endsection