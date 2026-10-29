@extends('layouts.storefront', ['title' => 'Pagamento'])

@section('content')

    <section class="page-head"><div class="container"><span class="eyebrow">Pedido #{{ $order->id }}</span><h1>Pagamento</h1><p>Revise o total e siga para o pagamento seguro.</p></div></section>
    <section class="container auth pb-20">
        <article class="summary"><h2>Resumo do pedido</h2><p class="summary__row"><span>Pedido</span><span>#{{ $order->id }}</span></p><p class="summary__row summary__row--total"><strong>Total</strong><strong>R$ {{ number_format((float) $order->total, 2, ',', '.') }}</strong></p>
            @if ($errors->any())<p class="form-error" role="alert">{{ $errors->first() }}</p>@endif
            <form action="{{ route('payment.store', $order) }}" method="POST" class="mt-5">@csrf<button class="btn btn--primary btn--block" type="submit">Pagar com Mercado Pago</button></form>
            <a class="btn btn--ghost btn--block mt-3" href="{{ route('orders.show', $order) }}">Voltar ao pedido</a>
        </article>
    </section>
