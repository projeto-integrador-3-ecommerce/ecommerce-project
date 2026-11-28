@extends('layouts.storefront', ['title' => auth()->user()->is_admin ? 'Pedidos' : 'Meus pedidos'])

@section('content')
<section><div><span>Sua conta</span><h1>{{ auth()->user()->is_admin ? 'Pedidos' : 'Meus pedidos' }}</h1><p>Acompanhe o status e os detalhes de cada pedido.</p></div></section>
<section>
    @forelse ($orders as $order)
        <article>
            <div>
                <div><h2>Pedido #{{ $order->id }}</h2><p>{{ $order->created_at->format('d/m/Y H:i') }}</p></div>
                <span>{{ $order->statusLabel() }}</span>
            </div>
            @if (auth()->user()->is_admin)<p>Cliente: {{ $order->user->name }}</p>@endif
            <p>Total: R$ {{ number_format((float) $order->total, 2, ',', '.') }}</p>
            <a href="{{ route('orders.show', $order) }}">Ver pedido</a>
        </article>
    @empty
        <div><h2>Você ainda não tem pedidos</h2><p>Quando fizer uma compra, o acompanhamento aparecerá aqui.</p><a href="{{ route('products.index') }}">Ir para o catálogo</a></div>
    @endforelse
    <div>{{ $orders->links() }}</div>
</section>
@endsection
