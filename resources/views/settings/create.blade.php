@extends('layouts.storefront', ['title' => 'Dados da loja'])

@section('content')
<section class="page-head">
    <div class="container">
        <span class="eyebrow">Administração da loja</span>
        <h1>Dados da loja</h1>
        <p>{{ $setting ? 'Atualize as informações exibidas sobre a loja.' : 'Cadastre as informações da loja exibidas no site.' }}</p>
        <div class="admin-page-actions">
            <a class="btn btn--outline" href="{{ route('admin.dashboard') }}">Voltar ao painel</a>
        </div>
    </div>
</section>
<section class="container pb-20">
    @include('settings._form', ['setting' => $setting])
</section>
@endsection
