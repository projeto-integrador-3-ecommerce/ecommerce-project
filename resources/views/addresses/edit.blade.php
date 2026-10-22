@extends('layouts.storefront', ['title' => 'Editar endereço'])

@section('content')
<section class="page-head"><div class="container"><span class="eyebrow">Sua conta</span><h1>Editar endereço</h1><p>Atualize os dados de entrega.</p></div></section>
<section class="container auth pb-20">
    @if ($errors->any())<p class="form-error" role="alert">{{ $errors->first() }}</p>@endif
    <form action="{{ route('addresses.update', $address) }}" method="POST">@csrf @method('PUT')
        <label class="field"><span>CEP</span><input class="input" type="text" name="cep" id="cep" value="{{ old('cep', $address->cep) }}" autocomplete="postal-code" required></label>
        <label class="field"><span>Rua / logradouro</span><input class="input" type="text" name="street" id="street" value="{{ old('street', $address->street) }}" autocomplete="address-line1" required></label>
        <label class="field"><span>Número</span><input class="input" type="text" name="number" id="number" value="{{ old('number', $address->number) }}" required></label>
        <label class="field"><span>Complemento</span><input class="input" type="text" name="complement" id="complement" value="{{ old('complement', $address->complement) }}" autocomplete="address-line2"></label>
        <label class="field"><span>Bairro</span><input class="input" type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood', $address->neighborhood) }}" required></label>
        <label class="field"><span>Cidade</span><input class="input" type="text" name="city" id="city" value="{{ old('city', $address->city) }}" autocomplete="address-level2" required></label>
        <label class="field"><span>Estado</span><select class="input" name="state" id="state" autocomplete="address-level1" required>
            <option value="">Selecione</option>
            @foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $state)<option value="{{ $state }}" @selected(old('state', $address->state) === $state)>{{ $state }}</option>@endforeach
        </select></label>
        <label class="field"><span>País</span><select class="input" name="country" id="country" required>
            @foreach (['br' => 'Brasil', 'eua' => 'Estados Unidos', 'uk' => 'Reino Unido', 'kr' => 'Coreia do Sul', 'cn' => 'China', 'jp' => 'Japão'] as $code => $country)<option value="{{ $code }}" @selected(old('country', $address->country) === $code)>{{ $country }}</option>@endforeach
        </select></label>
        <button class="btn btn--primary btn--block" type="submit">Salvar alterações</button>
    </form>
    <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="mt-3">@csrf @method('DELETE')<button class="btn btn--ghost btn--block" type="submit">Remover endereço</button></form>
    <a class="btn btn--ghost btn--block mt-3" href="{{ route('addresses.index') }}">Voltar aos endereços</a>
</section>
@endsection
