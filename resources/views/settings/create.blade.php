@extends('layouts.storefront', ['title' => 'Dados da loja'])

@section('content')
<section>
    <div>
        <span>Administração da loja</span>
        <h1>Dados da loja</h1>
        <p>{{ $setting ? 'Atualize as informações exibidas sobre a loja.' : 'Cadastre as informações da loja exibidas no site.' }}</p>
        <div>
            <a href="{{ route('admin.dashboard') }}">Voltar ao painel</a>
        </div>
    </div>
</section>
<section>
    @include('settings._form', ['setting' => $setting])
</section>
@endsection
