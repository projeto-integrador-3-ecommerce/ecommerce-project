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
@extends('layouts.storefront', ['title' => 'Pedido #'.$order->id])

@section('content')
<nav aria-label="Trilha de navegação" class="mb-6 flex flex-wrap items-center gap-2 text-xs text-muted">
    <a href="{{ route('storefront.home') }}" class="rounded-full px-2 py-1 transition hover:text-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Página Inicial</a>
    <span aria-hidden="true">›</span>
    <a href="{{ route('orders.index') }}" class="rounded-full px-2 py-1 transition hover:text-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Pedidos</a>
    <span aria-hidden="true">›</span>
    <span class="font-semibold text-navy">Pedido #{{ $order->id }}</span>
</nav>

<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Acompanhe sua compra</span>
    <div class="mt-2 flex flex-wrap items-center gap-3">
        <h1 class="text-3xl font-semibold text-navy sm:text-4xl">Pedido #{{ $order->id }}</h1>
        <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $statusStyles[$order->status] ?? 'bg-muted/20 text-navy' }}">{{ $order->statusLabel() }}</span>
    </div>
    <p class="mt-2 text-xs text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</p>
</section>

<section class="mt-8 grid items-start gap-6 lg:grid-cols-[1fr_20rem]">
    <div class="space-y-6">
        @if (session('status'))
            <p role="status" class="rounded-2xl bg-teal/10 px-4 py-3 text-sm font-semibold text-teal">{{ session('status') }}</p>
        @endif
        <article class="rounded-3xl border border-aqua bg-white p-5 shadow-sm sm:p-6">
            <h2 class="text-base font-semibold text-navy">Itens do pedido</h2>
            <div class="mt-5 divide-y divide-aqua">
                @foreach ($order->orderItems as $item)
                    <div class="flex items-center justify-between gap-4 py-3 text-sm">
                        <span class="text-muted">{{ $item->product?->name ?? 'Produto removido' }} × {{ $item->quantity }}</span>
                        <span class="font-semibold text-navy">R$ {{ number_format((float) ($item->product?->price ?? 0) * $item->quantity, 2, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 flex items-center justify-between border-t border-navy/10 pt-4 text-base">
                <strong class="text-navy">Total</strong>
                <strong class="text-navy">R$ {{ number_format((float) $order->total, 2, ',', '.') }}</strong>
            </div>
            <p class="mt-3 text-xs text-muted">Pagamento: {{ $order->payment?->method ?? 'Ainda não informado' }}</p>
        </article>

        <x-ui.address-card :address="$order->address" title="Endereço de envio" :edit-route="$order->address ? route('orders.addresses', $order) : null" edit-label="Alterar endereço" />

        @can('admin')
            <article class="rounded-3xl border border-aqua bg-white p-5 shadow-sm sm:p-6">
                <h2 class="text-base font-semibold text-navy">Atualizar status</h2>
                <form action="{{ route('orders.status', $order) }}" method="POST" class="mt-5 flex flex-wrap items-end gap-3">@csrf @method('PATCH')
                    <x-ui.select id="status" name="status" label="Status do pedido" class="min-w-56 flex-1">
                        @foreach(\App\Models\Order::STATUS_LABELS as $value => $label)<option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>@endforeach
                    </x-ui.select>
                    <x-ui.button type="submit" size="md">Salvar status</x-ui.button>
                </form>
            </article>
        @endcan
    </div>

    <aside class="self-start rounded-3xl border border-aqua bg-white p-5 shadow-sm sm:p-6 lg:sticky lg:top-28">
        <h2 class="text-base font-semibold text-navy">Próximo passo</h2>
        <div class="mt-4 space-y-3 text-sm text-muted">
            @if ($order->status === \App\Models\Order::STATUS_AWAITING_PAYMENT && $order->address)
                <p>O endereço está selecionado. Você já pode seguir para o pagamento.</p>
                <x-ui.link href="{{ route('payment.create', $order) }}" class="w-full">Finalizar pagamento</x-ui.link>
            @elseif ($order->status === \App\Models\Order::STATUS_AWAITING_PAYMENT)
                <p>Escolha um endereço de entrega para continuar.</p>
                <x-ui.link href="{{ route('orders.addresses', $order) }}" class="w-full">Escolher endereço</x-ui.link>
            @else
                <p>Acompanhe as atualizações do seu pedido nesta página.</p>
            @endif
            @if ($order->status === \App\Models\Order::STATUS_AWAITING_PAYMENT && $order->user_id === auth()->id())
                <form action="{{ route('orders.destroy', $order) }}" method="POST">@csrf @method('DELETE')<x-ui.button type="submit" variant="danger" class="w-full">Cancelar pedido</x-ui.button></form>
            @endif
            <div class="flex flex-wrap gap-2 pt-2">
                <x-ui.link href="{{ route('orders.index') }}" variant="outline" size="sm">Meus pedidos</x-ui.link>
                <x-ui.link href="{{ route('products.index') }}" variant="ghost" size="sm">Voltar ao catálogo</x-ui.link>
            </div>
        </div>
    </aside>
</section>
@endsection
