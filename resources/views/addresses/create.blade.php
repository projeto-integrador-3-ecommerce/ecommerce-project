@extends('layouts.storefront', ['title' => 'Cadastrar endereço'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Sua conta</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Cadastrar endereço</h1>
    <p class="mt-3 text-sm text-muted">Informe onde deseja receber seus pedidos.</p>
</section>
<section class="mt-8">
    @if ($errors->any())
        <p role="alert" class="mb-5 rounded-2xl bg-red/10 px-4 py-3 text-sm font-semibold text-red">{{ $errors->first() }}</p>
    @endif
    <form action="{{ route('addresses.store') }}" method="POST" class="rounded-3xl border border-aqua bg-white p-5 shadow-sm sm:p-8">@csrf
        @if ($order)<input type="hidden" name="order_id" value="{{ $order->id }}">@endif
        <div class="grid gap-5 sm:grid-cols-2">
            <x-ui.input label="CEP" type="text" name="cep" id="cep" value="{{ old('cep') }}" placeholder="00000-000" autocomplete="postal-code" required />
            <x-ui.input label="Número" type="text" name="number" id="number" value="{{ old('number') }}" required />
            <x-ui.input label="Rua / logradouro" type="text" name="street" id="street" value="{{ old('street') }}" autocomplete="address-line1" required class="sm:col-span-2" />
            <x-ui.input label="Complemento" type="text" name="complement" id="complement" value="{{ old('complement') }}" autocomplete="address-line2" class="sm:col-span-2" />
            <x-ui.input label="Bairro" type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood') }}" required />
            <x-ui.input label="Cidade" type="text" name="city" id="city" value="{{ old('city') }}" autocomplete="address-level2" required />
            <x-ui.select name="state" id="state" label="Estado" autocomplete="address-level1" required>
                <option value="">Selecione</option>
                @foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $state)<option value="{{ $state }}" @selected(old('state') === $state)>{{ $state }}</option>@endforeach
            </x-ui.select>
            <x-ui.select name="country" id="country" label="País" required>
                <option value="br" @selected(old('country', 'br') === 'br')>Brasil</option>
            </x-ui.select>
        </div>
        <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-aqua pt-6">
            <x-ui.button type="submit" size="lg">Salvar endereço</x-ui.button>
            <x-ui.link href="{{ $order ? route('orders.addresses', $order) : route('addresses.index') }}" variant="ghost" size="sm">{{ $order ? 'Voltar à seleção do pedido' : 'Voltar aos endereços' }}</x-ui.link>
        </div>
    </form>
</section>
@endsection
