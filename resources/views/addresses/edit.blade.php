@extends('layouts.storefront', ['title' => 'Editar endereço'])

@section('content')
<section><div><span>Sua conta</span><h1>Editar endereço</h1><p>Atualize os dados de entrega.</p></div></section>
<section>
    @if ($errors->any())<p role="alert">{{ $errors->first() }}</p>@endif
    <form action="{{ route('addresses.update', $address) }}" method="POST">@csrf @method('PUT')
        <label><span>CEP</span><input type="text" name="cep" id="cep" value="{{ old('cep', $address->cep) }}" autocomplete="postal-code" required></label>
        <label><span>Rua / logradouro</span><input type="text" name="street" id="street" value="{{ old('street', $address->street) }}" autocomplete="address-line1" required></label>
        <label><span>Número</span><input type="text" name="number" id="number" value="{{ old('number', $address->number) }}" required></label>
        <label><span>Complemento</span><input type="text" name="complement" id="complement" value="{{ old('complement', $address->complement) }}" autocomplete="address-line2"></label>
        <label><span>Bairro</span><input type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood', $address->neighborhood) }}" required></label>
        <label><span>Cidade</span><input type="text" name="city" id="city" value="{{ old('city', $address->city) }}" autocomplete="address-level2" required></label>
        <label><span>Estado</span><select name="state" id="state" autocomplete="address-level1" required>
            <option value="">Selecione</option>
            @foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $state)<option value="{{ $state }}" @selected(old('state', $address->state) === $state)>{{ $state }}</option>@endforeach
        </select></label>
        <label><span>País</span><select name="country" id="country" required>
            @foreach (['br' => 'Brasil', 'eua' => 'Estados Unidos', 'uk' => 'Reino Unido', 'kr' => 'Coreia do Sul', 'cn' => 'China', 'jp' => 'Japão'] as $code => $country)<option value="{{ $code }}" @selected(old('country', $address->country) === $code)>{{ $country }}</option>@endforeach
        </select></label>
        <button type="submit">Salvar alterações</button>
    </form>
    <form action="{{ route('addresses.destroy', $address) }}" method="POST">@csrf @method('DELETE')<button type="submit">Remover endereço</button></form>
    <a href="{{ route('addresses.index') }}">Voltar aos endereços</a>
</section>
@endsection
