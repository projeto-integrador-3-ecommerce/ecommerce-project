@extends('layouts.storefront', ['title' => 'Endereço de entrega'])

@section('content')
<nav aria-label="Trilha de navegação" class="mb-6 flex flex-wrap items-center gap-2 text-xs text-muted">
    <a href="{{ route('orders.show', $order) }}" class="rounded-full px-2 py-1 transition hover:text-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Pedido #{{ $order->id }}</a>
    <span aria-hidden="true">›</span>
    <span class="font-semibold text-navy">Endereço de entrega</span>
</nav>

<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Pedido #{{ $order->id }}</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Endereço de entrega</h1>
    <p class="mt-3 text-sm text-muted">Selecione onde deseja receber seu pedido.</p>
</section>

<section class="mt-8">
    <p class="mb-5"><x-ui.link href="{{ route('addresses.create', ['order_id' => $order->id]) }}" size="sm">Cadastrar novo endereço</x-ui.link></p>
    @if ($addresses->isNotEmpty())
        <form action="{{ route('orders.address', $order) }}" method="POST" class="grid gap-4 md:grid-cols-2">@csrf
            @foreach ($addresses as $address)
                <label class="flex min-h-32 cursor-pointer items-start gap-3 rounded-3xl border border-navy/25 bg-white p-5 shadow-sm transition has-[:checked]:border-blue has-[:checked]:bg-blue/5 has-[:checked]:ring-2 has-[:checked]:ring-blue/30 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue">
                    <input type="radio" name="address_id" value="{{ $address->id }}" required class="mt-1 size-5 accent-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
                    <span>
                        <strong class="block text-sm font-semibold text-navy">{{ $address->street }}, {{ $address->number }}</strong>
                        <span class="mt-2 block text-xs text-muted">{{ $address->neighborhood }}, {{ $address->city }} - {{ $address->state }} · CEP {{ $address->cep }}</span>
                    </span>
                </label>
            @endforeach
            <div class="md:col-span-2">
                <x-ui.button type="submit" size="lg">Usar este endereço</x-ui.button>
            </div>
        </form>
    @else
        <div class="rounded-3xl border border-aqua bg-white p-8 text-center shadow-sm">
            <h2 class="text-xl font-semibold text-navy">Nenhum endereço cadastrado</h2>
            <p class="mt-2 text-sm text-muted">Cadastre um endereço para continuar com o pedido.</p>
            <x-ui.link href="{{ route('addresses.create', ['order_id' => $order->id]) }}" class="mt-6">Cadastrar endereço</x-ui.link>
        </div>
    @endif
    <x-ui.link href="{{ route('orders.show', $order) }}" variant="ghost" size="sm" class="mt-6">Voltar ao pedido</x-ui.link>
</section>
@endsection
