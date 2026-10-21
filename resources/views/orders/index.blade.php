@extends('layouts.storefront', ['title' => auth()->user()->is_admin ? 'Pedidos' : 'Meus pedidos'])

@section('content')
<section class="page-head"><div class="container"><span class="eyebrow">Sua conta</span><h1>{{ auth()->user()->is_admin ? 'Pedidos' : 'Meus pedidos' }}</h1><p>Acompanhe o status e os detalhes de cada pedido.</p></div></section>
<section class="container pb-20">
    @forelse ($orders as $order)
        <article class="info-card mb-4">
            <div class="section__head">
                <div><h2>Pedido #{{ $order->id }}</h2><p class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</p></div>
                <span class="status-pill">{{ $order->statusLabel() }}</span>
            </div>
            @if (auth()->user()->is_admin)<p>Cliente: {{ $order->user->name }}</p>@endif
            <p>Total: R$ {{ number_format((float) $order->total, 2, ',', '.') }}</p>
            <a class="btn btn--outline mt-4" href="{{ route('orders.show', $order) }}">Ver pedido</a>
        </article>
    @empty
        <div class="empty-state"><h2>Você ainda não tem pedidos</h2><p>Quando fizer uma compra, o acompanhamento aparecerá aqui.</p><a class="btn btn--primary" href="{{ route('products.index') }}">Ir para o catálogo</a></div>
    @endforelse
    <div class="mt-6">{{ $orders->links() }}</div>
</section>
@endsection
