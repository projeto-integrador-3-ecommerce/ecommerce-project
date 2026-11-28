@extends('layouts.storefront', ['title' => 'Pedido #'.$order->id])

@section('content')

    <section><div><span>Acompanhe sua compra</span><h1>Pedido #{{ $order->id }}</h1><p>{{ $order->created_at->format('d/m/Y H:i') }}</p><span>{{ $order->statusLabel() }}</span></div></section>
    <section>
        <div>
            @if (session('status'))<p role="status">{{ session('status') }}</p>@endif
            <article><h2>Itens do pedido</h2>
                @foreach ($order->orderItems as $item)
                    <div><span>{{ $item->product?->name ?? 'Produto removido' }} × {{ $item->quantity }}</span><span>R$ {{ number_format((float) ($item->product?->price ?? 0) * $item->quantity, 2, ',', '.') }}</span></div>
                @endforeach
                <p><strong>Total</strong><strong>R$ {{ number_format((float) $order->total, 2, ',', '.') }}</strong></p>
                <p>Pagamento: {{ $order->payment?->method ?? 'Ainda não informado' }}</p>
            </article>
            <article><h2>Endereço de entrega</h2>
                @if ($order->address)
                    <p>{{ $order->address->street }}, {{ $order->address->number }}@if($order->address->complement), {{ $order->address->complement }}@endif</p>
                    <p>{{ $order->address->neighborhood }}, {{ $order->address->city }} - {{ $order->address->state }}, CEP {{ $order->address->cep }}</p>
                @else
                    <p>Nenhum endereço selecionado.</p>
                @endif
            </article>
            @can('admin')
                <article><h2>Atualizar status</h2><form action="{{ route('orders.status', $order) }}" method="POST">@csrf @method('PATCH')
                    <label><span>Status do pedido</span><select id="status" name="status">@foreach(\App\Models\Order::STATUS_LABELS as $value => $label)<option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>@endforeach</select></label>
                    <button type="submit">Salvar status</button>
                </form></article>
            @endcan
        </div>
        <aside><h2>Próximo passo</h2>
            @if ($order->status === \App\Models\Order::STATUS_AWAITING_PAYMENT && $order->address)
                <p>O endereço está selecionado. Você já pode seguir para o pagamento.</p><a href="{{ route('payment.create', $order) }}">Finalizar pagamento</a>
            @elseif ($order->status === \App\Models\Order::STATUS_AWAITING_PAYMENT)
                <p>Escolha um endereço de entrega para continuar.</p><a href="{{ route('orders.addresses', $order) }}">Escolher endereço</a>
            @else
                <p>Acompanhe as atualizações do seu pedido nesta página.</p>
            @endif
            @if ($order->status === \App\Models\Order::STATUS_AWAITING_PAYMENT && $order->user_id === auth()->id())
                <form action="{{ route('orders.destroy', $order) }}" method="POST">@csrf @method('DELETE')<button type="submit">Cancelar pedido</button></form>
            @endif
            <a href="{{ route('orders.index') }}">Meus pedidos</a>
            <a href="{{ route('products.index') }}">Voltar ao catálogo</a>
        </aside>
    </section>

@endsection
