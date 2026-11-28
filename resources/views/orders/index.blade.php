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
@extends('layouts.storefront', ['title' => auth()->user()->is_admin ? 'Pedidos' : 'Meus pedidos'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Sua conta</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">{{ auth()->user()->is_admin ? 'Pedidos' : 'Meus pedidos' }}</h1>
    <p class="mt-3 text-sm text-muted">Acompanhe o status e os detalhes de cada pedido.</p>
</section>

<section class="mt-8">
    <div class="hidden overflow-x-auto rounded-3xl border border-aqua bg-white shadow-sm md:block">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead class="bg-mist/60 text-xs text-navy">
                <tr><th scope="col" class="px-5 py-4 font-semibold">Pedido</th><th scope="col" class="px-5 py-4 font-semibold">Data</th>@if (auth()->user()->is_admin)<th scope="col" class="px-5 py-4 font-semibold">Cliente</th>@endif<th scope="col" class="px-5 py-4 font-semibold">Status</th><th scope="col" class="px-5 py-4 font-semibold">Total</th><th scope="col" class="px-5 py-4 font-semibold">Ações</th></tr>
            </thead>
            <tbody class="divide-y divide-aqua">
                @foreach ($orders as $order)
                    <tr class="transition hover:bg-bg/70">
                        <td class="px-5 py-4 font-semibold text-navy">Pedido #{{ $order->id }}</td>
                        <td class="px-5 py-4 text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        @if (auth()->user()->is_admin)<td class="px-5 py-4 text-muted">{{ $order->user->name }}</td>@endif
                        <td class="px-5 py-4"><span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $statusStyles[$order->status] ?? 'bg-muted/20 text-navy' }}">{{ $order->statusLabel() }}</span></td>
                        <td class="px-5 py-4 font-semibold text-navy">R$ {{ number_format((float) $order->total, 2, ',', '.') }}</td>
                        <td class="px-5 py-4"><a href="{{ route('orders.show', $order) }}" class="inline-flex min-h-11 items-center rounded-full px-3 text-xs font-semibold text-navy underline decoration-blue/40 underline-offset-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Ver pedido</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="grid gap-4 md:hidden">
        @forelse ($orders as $order)
            <article class="rounded-3xl border border-aqua bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div><h2 class="text-sm font-semibold text-navy">Pedido #{{ $order->id }}</h2><p class="mt-1 text-xs text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</p></div>
                    <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $statusStyles[$order->status] ?? 'bg-muted/20 text-navy' }}">{{ $order->statusLabel() }}</span>
                </div>
                @if (auth()->user()->is_admin)<p class="mt-3 text-xs text-muted">Cliente: {{ $order->user->name }}</p>@endif
                <p class="mt-3 text-sm font-bold text-navy">Total: R$ {{ number_format((float) $order->total, 2, ',', '.') }}</p>
                <x-ui.link href="{{ route('orders.show', $order) }}" variant="outline" size="sm" class="mt-4">Ver pedido</x-ui.link>
            </article>
        @empty
            <div class="rounded-3xl border border-aqua bg-white p-8 text-center shadow-sm">
                <h2 class="text-xl font-semibold text-navy">Você ainda não tem pedidos</h2>
                <p class="mt-2 text-sm text-muted">Quando fizer uma compra, o acompanhamento aparecerá aqui.</p>
                <x-ui.link href="{{ route('products.index') }}" class="mt-6">Ir para o catálogo</x-ui.link>
            </div>
        @endforelse
    </div>
    <div class="mt-6 flex flex-wrap gap-2 [&_a]:inline-flex [&_a]:min-h-11 [&_a]:items-center [&_a]:rounded-full [&_a]:border [&_a]:border-navy/20 [&_a]:px-3 [&_a]:text-xs [&_a]:font-semibold [&_a]:text-navy [&_a]:hover:bg-aqua/40 [&_span]:inline-flex [&_span]:min-h-11 [&_span]:items-center [&_span]:px-2 [&_span]:text-xs [&_span]:text-muted">{{ $orders->links() }}</div>
</section>
@endsection
