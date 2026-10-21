@extends('layouts.storefront', ['title' => 'Cadastrar endereço'])

@section('content')
<section class="page-head"><div class="container"><span class="eyebrow">Sua conta</span><h1>Cadastrar endereço</h1><p>Informe onde deseja receber seus pedidos.</p></div></section>
<section class="container auth pb-20">
    @if ($errors->any())<p class="form-error" role="alert">{{ $errors->first() }}</p>@endif
    <form action="{{ route('addresses.store') }}" method="POST">@csrf
        <label class="field"><span>CEP</span><input class="input" type="text" name="cep" id="cep" value="{{ old('cep') }}" placeholder="00000-000" autocomplete="postal-code" required></label>
        <label class="field"><span>Rua / logradouro</span><input class="input" type="text" name="street" id="street" value="{{ old('street') }}" autocomplete="address-line1" required></label>
        <label class="field"><span>Número</span><input class="input" type="text" name="number" id="number" value="{{ old('number') }}" required></label>
        <label class="field"><span>Complemento</span><input class="input" type="text" name="complement" id="complement" value="{{ old('complement') }}" autocomplete="address-line2"></label>
        <label class="field"><span>Bairro</span><input class="input" type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood') }}" required></label>
        <label class="field"><span>Cidade</span><input class="input" type="text" name="city" id="city" value="{{ old('city') }}" autocomplete="address-level2" required></label>
        <label class="field"><span>Estado</span><select class="input" name="state" id="state" autocomplete="address-level1" required>
            <option value="">Selecione</option>
            @foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $state)<option value="{{ $state }}" @selected(old('state') === $state)>{{ $state }}</option>@endforeach
        </select></label>
        <label class="field"><span>País</span><select class="input" name="country" id="country" required><option value="br" @selected(old('country', 'br') === 'br')>Brasil</option></select></label>
        <button class="btn btn--primary btn--block" type="submit">Salvar endereço</button>
        <a class="btn btn--ghost btn--block mt-3" href="{{ route('addresses.index') }}">Voltar aos endereços</a>
    </form>
</section>
@endsection
