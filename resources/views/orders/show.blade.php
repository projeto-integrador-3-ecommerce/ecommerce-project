@extends('layouts.app')

@section('content')

    <h1>Pedido #{{ $order->id }}</h1>
    <p>Status: {{ $order->statusLabel() }}</p>
    <p>Realizado em: {{ $order->created_at->format('d/m/Y H:i') }}</p>

    @if(session('status'))
        <p role="status">{{ session('status') }}</p>
    @endif

    <h2>Itens do pedido</h2>
    @foreach($order->orderItems as $item)
        <p>{{ $item->product?->name ?? 'Produto removido' }} - Quantidade: {{ $item->quantity }}</p>
    @endforeach

    <p>
        Forma de pagamento:
        @if($order->payment)
            @switch($order->payment->method)
                @case('cartao')
                    Cartão de crédito/débito
                    @break
                @case('boleto')
                    Boleto
                    @break
                @case('pix')
                    PIX
                    @break
                @default
                    {{ $order->payment->method }}
            @endswitch
        @else
            Não informado
        @endif
    </p>

    <h2>Endereço de entrega:</h2>
    @if($order->address)
        <p>
            {{ $order->address->street }},
            {{ $order->address->number }}
            @if($order->address->complement)
                - {{ $order->address->complement }}
            @endif
            <br>
            {{ $order->address->neighborhood }},
            {{ $order->address->city }} - {{ $order->address->state }}
            <br>
            CEP: {{ $order->address->cep }}
        </p>
    @else
        <p>Não informado</p>
    @endif

    <p>Total do pedido: R${{ $order->total }}</p>

    @if($order->status === 'to_pay' && $order->address)
        <a href="{{ route('payment.create', $order) }}">Pagar pedido</a>
    @elseif($order->status === 'to_pay')
        <a href="{{ route('orders.addresses', $order) }}">Adicionar endereço</a>
    @else
        <a href="{{ route('products.index') }}">Voltar às compras</a>
    @endif

    @if($order->status === 'to_pay' && $order->user_id === auth()->id())
        <form action="{{ route('orders.destroy', $order) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Cancelar compra</button>
        </form>
    @endif

    @can('admin')
        <form action="{{ route('orders.status', $order) }}" method="POST">
            @csrf
            @method('PATCH')
            <label for="status">Atualizar status</label>
            <select id="status" name="status">
                @foreach(\App\Models\Order::STATUS_LABELS as $value => $label)
                    <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit">Salvar status</button>
        </form>
    @endcan

    <a href="{{ route('orders.index') }}">Voltar aos pedidos</a>

@endsection
