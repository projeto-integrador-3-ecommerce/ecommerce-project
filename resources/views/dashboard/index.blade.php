@extends('layouts.app')

@section('content')

    <h1>Dashboard Dimensiona</h1>
    <section id="orders">
        @foreach($orders as $order)
            <div>
                <h2>ID do pedido: #{{ $order->id}}</h2>
                <p>Status do pedido: {{ $order->status }}</p>
                <p>Usuário do pedido: {{ $order->user->name }}</p>
                <p>Endereço do pedido: {{ $order->address->street ?? 'Endereço não encontrado' }}</p>
                <p>Criado em: {{ $order->created_at }}</p>
                <p>Atualizado em: {{ $order->created_at }}</p>
            </div>
        @endforeach
    </section>

@endsection