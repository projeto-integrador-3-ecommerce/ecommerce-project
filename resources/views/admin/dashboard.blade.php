@extends('layouts.storefront', ['title' => 'Painel administrativo'])

@section('content')
<section><div><span>Administração</span><h1>Painel administrativo</h1><p>Visão geral da loja, dos clientes e dos pedidos.</p></div></section>
<section>
    <div>
        <a href="{{ route('users.index') }}"><span>Usuários cadastrados</span><strong>{{ $usersCount }}</strong><p>Ver e gerenciar contas de clientes</p></a>
        <a href="{{ route('orders.index') }}"><span>Pedidos no total</span><strong>{{ $ordersCount }}</strong><p>Consultar todos os pedidos da loja</p></a>
        <a href="{{ route('products.index') }}"><span>Produtos</span><strong>{{ $productsCount }}</strong><p>Ver o catálogo da loja</p></a>
    </div>

    <div>
        <a href="{{ route('products.create') }}">Cadastrar produto</a>
        <a href="{{ route('categories.create') }}">Criar categoria</a>
        <a href="{{ route('setting.create') }}">Dados da loja</a>
    </div>

    <div><div><span>Acompanhamento</span><h2>Pedidos em andamento: {{ $openOrdersCount }}</h2></div><a href="{{ route('orders.index') }}">Ver todos os pedidos →</a></div>

    @forelse ($recentOrders as $order)
        <article>
            <div>
                <div><h3>Pedido #{{ $order->id }}</h3><p>{{ $order->user->name }} · {{ $order->created_at->format('d/m/Y H:i') }}</p></div>
                <span>{{ $order->statusLabel() }}</span>
            </div>
            <p>Total: R$ {{ number_format((float) $order->total, 2, ',', '.') }}</p>
            <a href="{{ route('orders.show', $order) }}">Ver pedido</a>
        </article>
    @empty
        <div><h2>Nenhum pedido ainda</h2><p>Os pedidos feitos na loja aparecerão aqui.</p></div>
    @endforelse
</section>
@endsection
