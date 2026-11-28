@extends('layouts.storefront', ['title' => 'Editar dados da loja'])

@section('content')
<section>
    <div>
        <span>Administração da loja</span>
        <h1>Editar dados da loja</h1>
        <p>Atualize as informações exibidas sobre a loja.</p>
        <div>
            <a href="{{ route('admin.dashboard') }}">Voltar ao painel</a>
        </div>
    </div>
</section>
<section>
    @include('settings._form', ['setting' => $setting])
</section>
@endsection
