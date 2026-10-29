@extends('layouts.storefront', ['title' => 'Painel administrativo'])

@section('content')
<section class="page-head"><div class="container"><span class="eyebrow">Administração</span><h1>Painel administrativo</h1><p>Visão geral da loja, dos clientes e dos pedidos.</p></div></section>
<section class="container pb-20">
    <div class="info-grid">
        <a class="info-card" href="{{ route('users.index') }}"><span>Usuários cadastrados</span><strong>{{ $usersCount }}</strong><p>Ver e gerenciar contas de clientes</p></a>
        <a class="info-card" href="{{ route('orders.index') }}"><span>Pedidos no total</span><strong>{{ $ordersCount }}</strong><p>Consultar todos os pedidos da loja</p></a>
        <a class="info-card" href="{{ route('products.index') }}"><span>Produtos</span><strong>{{ $productsCount }}</strong><p>Ver o catálogo da loja</p></a>
    </div>

    <div class="section__head mt-8"><div><span class="eyebrow">Acompanhamento</span><h2 class="section__title">Pedidos em andamento: {{ $openOrdersCount }}</h2></div><a class="section__link" href="{{ route('orders.index') }}">Ver todos os pedidos →</a></div>

    @forelse ($recentOrders as $order)
        <article class="info-card mb-4">
            <div class="section__head">
                <div><h3>Pedido #{{ $order->id }}</h3><p>{{ $order->user->name }} · {{ $order->created_at->format('d/m/Y H:i') }}</p></div>
                <span class="status-pill">{{ $order->statusLabel() }}</span>
            </div>
            <p>Total: R$ {{ number_format((float) $order->total, 2, ',', '.') }}</p>
            <a class="btn btn--outline mt-4" href="{{ route('orders.show', $order) }}">Ver pedido</a>
        </article>
    @empty
        <div class="empty-state"><h2>Nenhum pedido ainda</h2><p>Os pedidos feitos na loja aparecerão aqui.</p></div>
    @endforelse
</section>
@endsection
