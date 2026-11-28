@extends('layouts.storefront', ['title' => 'Editar endereço'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Sua conta</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Editar endereço</h1>
    <p class="mt-3 text-sm text-muted">Atualize os dados de entrega.</p>
</section>
<section class="mt-8">
    @if ($errors->any())
        <p role="alert" class="mb-5 rounded-2xl bg-red/10 px-4 py-3 text-sm font-semibold text-red">{{ $errors->first() }}</p>
    @endif
    <form action="{{ route('addresses.update', $address) }}" method="POST" class="rounded-3xl border border-aqua bg-white p-5 shadow-sm sm:p-8">@csrf @method('PUT')
        <div class="grid gap-5 sm:grid-cols-2">
            <x-ui.input label="CEP" type="text" name="cep" id="cep" value="{{ old('cep', $address->cep) }}" autocomplete="postal-code" required />
            <x-ui.input label="Número" type="text" name="number" id="number" value="{{ old('number', $address->number) }}" required />
            <x-ui.input label="Rua / logradouro" type="text" name="street" id="street" value="{{ old('street', $address->street) }}" autocomplete="address-line1" required class="sm:col-span-2" />
            <x-ui.input label="Complemento" type="text" name="complement" id="complement" value="{{ old('complement', $address->complement) }}" autocomplete="address-line2" class="sm:col-span-2" />
            <x-ui.input label="Bairro" type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood', $address->neighborhood) }}" required />
            <x-ui.input label="Cidade" type="text" name="city" id="city" value="{{ old('city', $address->city) }}" autocomplete="address-level2" required />
            <x-ui.select name="state" id="state" label="Estado" autocomplete="address-level1" required>
                <option value="">Selecione</option>
                @foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $state)<option value="{{ $state }}" @selected(old('state', $address->state) === $state)>{{ $state }}</option>@endforeach
            </x-ui.select>
            <x-ui.select name="country" id="country" label="País" required>
                @foreach (['br' => 'Brasil', 'eua' => 'Estados Unidos', 'uk' => 'Reino Unido', 'kr' => 'Coreia do Sul', 'cn' => 'China', 'jp' => 'Japão'] as $code => $country)<option value="{{ $code }}" @selected(old('country', $address->country) === $code)>{{ $country }}</option>@endforeach
            </x-ui.select>
        </div>
        <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-aqua pt-6">
            <x-ui.button type="submit" size="lg">Salvar alterações</x-ui.button>
            <x-ui.link href="{{ route('addresses.index') }}" variant="ghost" size="sm">Voltar aos endereços</x-ui.link>
        </div>
    </form>
    <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="mt-4">@csrf @method('DELETE')<x-ui.button type="submit" variant="danger" size="sm">Remover endereço</x-ui.button></form>
</section>
@endsection
