@extends('layouts.storefront', ['title' => 'Cadastrar endereço'])

@section('content')
<section><div><span>Sua conta</span><h1>Cadastrar endereço</h1><p>Informe onde deseja receber seus pedidos.</p></div></section>
<section>
    @if ($errors->any())<p role="alert">{{ $errors->first() }}</p>@endif
    <form action="{{ route('addresses.store') }}" method="POST">@csrf
        @if ($order)<input type="hidden" name="order_id" value="{{ $order->id }}">@endif
        <label><span>CEP</span><input type="text" name="cep" id="cep" value="{{ old('cep') }}" placeholder="00000-000" autocomplete="postal-code" required></label>
        <label><span>Rua / logradouro</span><input type="text" name="street" id="street" value="{{ old('street') }}" autocomplete="address-line1" required></label>
        <label><span>Número</span><input type="text" name="number" id="number" value="{{ old('number') }}" required></label>
        <label><span>Complemento</span><input type="text" name="complement" id="complement" value="{{ old('complement') }}" autocomplete="address-line2"></label>
        <label><span>Bairro</span><input type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood') }}" required></label>
        <label><span>Cidade</span><input type="text" name="city" id="city" value="{{ old('city') }}" autocomplete="address-level2" required></label>
        <label><span>Estado</span><select name="state" id="state" autocomplete="address-level1" required>
            <option value="">Selecione</option>
            @foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $state)<option value="{{ $state }}" @selected(old('state') === $state)>{{ $state }}</option>@endforeach
        </select></label>
        <label><span>País</span><select name="country" id="country" required><option value="br" @selected(old('country', 'br') === 'br')>Brasil</option></select></label>
        <button type="submit">Salvar endereço</button>
        <a href="{{ $order ? route('orders.addresses', $order) : route('addresses.index') }}">{{ $order ? 'Voltar à seleção do pedido' : 'Voltar aos endereços' }}</a>
    </form>
</section>
@endsection
