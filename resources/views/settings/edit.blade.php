@extends('layouts.storefront', ['title' => 'Editar dados da loja'])

@section('content')
<section class="page-head">
    <div class="container">
        <span class="eyebrow">Administração da loja</span>
        <h1>Editar dados da loja</h1>
        <p>Atualize as informações exibidas sobre a loja.</p>
        <div class="admin-page-actions">
            <a class="btn btn--outline" href="{{ route('admin.dashboard') }}">Voltar ao painel</a>
        </div>
    </div>
</section>
<section class="container pb-20">
    @include('settings._form', ['setting' => $setting])
</section>
@endsection
