@php
    $statusStyles = [
        'to_pay' => 'bg-red/10 text-red',
        'payment_confirmed' => 'bg-teal/10 text-teal',
        'shipped' => 'bg-blue/10 text-blue',
        'out_for_delivery' => 'bg-blue/10 text-blue',
        'delivered' => 'bg-teal/10 text-teal',
        'canceled' => 'bg-muted/20 text-navy',
    ];
@endphp
@extends('layouts.storefront', ['title' => 'Painel administrativo'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Administração</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Painel administrativo</h1>
    <p class="mt-3 text-sm text-muted">Visão geral da loja, dos clientes e dos pedidos.</p>
</section>

<section class="mt-8">
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ([['Usuários cadastrados', $usersCount, route('users.index'), 'Ver e gerenciar contas de clientes'], ['Pedidos no total', $ordersCount, route('orders.index'), 'Consultar todos os pedidos da loja'], ['Produtos', $productsCount, route('products.index'), 'Ver o catálogo da loja']] as $stat)
            <a href="{{ $stat[2] }}" class="rounded-3xl border border-aqua bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
                <span class="block text-xs font-semibold text-muted">{{ $stat[0] }}</span>
                <strong class="mt-2 block text-3xl font-semibold text-navy">{{ $stat[1] }}</strong>
                <span class="mt-3 block text-xs text-muted">{{ $stat[3] }}</span>
            </a>
        @endforeach
    </div>

    <div class="mt-6 flex flex-wrap gap-3 rounded-3xl border border-aqua bg-white p-5 shadow-sm">
        <x-ui.link href="{{ route('products.create') }}" size="sm">Cadastrar produto</x-ui.link>
        <x-ui.link href="{{ route('categories.create') }}" variant="outline" size="sm">Criar categoria</x-ui.link>
        <x-ui.link href="{{ route('setting.create') }}" variant="outline" size="sm">Dados da loja</x-ui.link>
    </div>

    <div class="mt-6 flex flex-wrap items-center justify-between gap-3 rounded-3xl bg-navy p-6 text-white">
        <div><span class="text-xs text-aqua">Acompanhamento</span><h2 class="mt-1 text-lg font-semibold">Pedidos em andamento: {{ $openOrdersCount }}</h2></div>
        <a href="{{ route('orders.index') }}" class="inline-flex min-h-11 items-center rounded-full border border-white/40 px-5 text-xs font-semibold transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Ver todos os pedidos →</a>
    </div>

    <div class="mt-6 grid gap-4 md:grid-cols-2">
        @forelse ($recentOrders as $order)
            <article class="rounded-3xl border border-aqua bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div><h3 class="text-sm font-semibold text-navy">Pedido #{{ $order->id }}</h3><p class="mt-1 text-xs text-muted">{{ $order->user->name }} · {{ $order->created_at->format('d/m/Y H:i') }}</p></div>
                    <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $statusStyles[$order->status] ?? 'bg-muted/20 text-navy' }}">{{ $order->statusLabel() }}</span>
                </div>
                <p class="mt-4 text-sm font-semibold text-navy">Total: R$ {{ number_format((float) $order->total, 2, ',', '.') }}</p>
                <x-ui.link href="{{ route('orders.show', $order) }}" variant="outline" size="sm" class="mt-4">Ver pedido</x-ui.link>
            </article>
        @empty
            <div class="md:col-span-2 rounded-3xl border border-aqua bg-white p-8 text-center shadow-sm">
                <h2 class="text-xl font-semibold text-navy">Nenhum pedido ainda</h2>
                <p class="mt-2 text-sm text-muted">Os pedidos feitos na loja aparecerão aqui.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection
