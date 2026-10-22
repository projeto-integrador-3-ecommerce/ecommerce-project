@extends('layouts.storefront', ['title' => 'Pedido #'.$order->id])

@section('content')

    <section class="page-head"><div class="container"><span class="eyebrow">Acompanhe sua compra</span><h1>Pedido #{{ $order->id }}</h1><p>{{ $order->created_at->format('d/m/Y H:i') }}</p><span class="status-pill">{{ $order->statusLabel() }}</span></div></section>
    <section class="container cart-layout pb-20">
        <div>
            @if (session('status'))<p class="status-pill mb-5" role="status">{{ session('status') }}</p>@endif
            <article class="info-card"><h2>Itens do pedido</h2>
                @foreach ($order->orderItems as $item)
                    <div class="summary__row"><span>{{ $item->product?->name ?? 'Produto removido' }} × {{ $item->quantity }}</span><span>R$ {{ number_format((float) ($item->product?->price ?? 0) * $item->quantity, 2, ',', '.') }}</span></div>
                @endforeach
                <p class="summary__row summary__row--total"><strong>Total</strong><strong>R$ {{ number_format((float) $order->total, 2, ',', '.') }}</strong></p>
                <p class="text-muted">Pagamento: {{ $order->payment?->method ?? 'Ainda não informado' }}</p>
            </article>
            <article class="info-card mt-5"><h2>Endereço de entrega</h2>
                @if ($order->address)
                    <p>{{ $order->address->street }}, {{ $order->address->number }}@if($order->address->complement), {{ $order->address->complement }}@endif</p>
                    <p>{{ $order->address->neighborhood }}, {{ $order->address->city }} - {{ $order->address->state }}, CEP {{ $order->address->cep }}</p>
                @else
                    <p class="text-muted">Nenhum endereço selecionado.</p>
                @endif
            </article>
            @can('admin')
                <article class="info-card mt-5"><h2>Atualizar status</h2><form action="{{ route('orders.status', $order) }}" method="POST">@csrf @method('PATCH')
                    <label class="field"><span>Status do pedido</span><select class="input" id="status" name="status">@foreach(\App\Models\Order::STATUS_LABELS as $value => $label)<option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>@endforeach</select></label>
                    <button class="btn btn--outline" type="submit">Salvar status</button>
                </form></article>
            @endcan
        </div>
        <aside class="summary"><h2>Próximo passo</h2>
            @if ($order->status === \App\Models\Order::STATUS_AWAITING_PAYMENT && $order->address)
                <p class="summary__ship">O endereço está selecionado. Você já pode seguir para o pagamento.</p><a class="btn btn--primary btn--block" href="{{ route('payment.create', $order) }}">Finalizar pagamento</a>
            @elseif ($order->status === \App\Models\Order::STATUS_AWAITING_PAYMENT)
                <p class="summary__ship">Escolha um endereço de entrega para continuar.</p><a class="btn btn--primary btn--block" href="{{ route('orders.addresses', $order) }}">Escolher endereço</a>
            @else
                <p class="summary__ship">Acompanhe as atualizações do seu pedido nesta página.</p>
            @endif
            @if ($order->status === \App\Models\Order::STATUS_AWAITING_PAYMENT && $order->user_id === auth()->id())
                <form action="{{ route('orders.destroy', $order) }}" method="POST" class="mt-4">@csrf @method('DELETE')<button class="link-btn" type="submit">Cancelar pedido</button></form>
            @endif
            <a class="btn btn--outline btn--block mt-4" href="{{ route('orders.index') }}">Meus pedidos</a>
            <a class="btn btn--ghost btn--block mt-2" href="{{ route('products.index') }}">Voltar ao catálogo</a>
        </aside>
    </section>

@endsection
