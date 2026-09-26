@extends('layouts.app')

@section('content')

    <h1>Informações da Loja:</h1>

    @if($setting)

        <form action="{{ route('setting.update', $setting) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label>Nome da Loja:</label>
                <input
                    type="text"
                    name="name"
                    value="{{ $setting->name }}"
                    placeholder="Ex: Dimensiona"
                >
            </div>

            <div>
                <label>CNPJ:</label>
                <input
                    type="text"
                    name="cnpj"
                    value="{{ $setting->cnpj }}"
                    placeholder="Ex: 000000000/0001-00"
                >
            </div>

            <div>
                <label>Email de Contato:</label>
                <input
                    type="text"
                    name="email"
                    value="{{ $setting->email }}"
                    placeholder="Ex: dimensiona@example.com"
                >
            </div>

            <div>
                <label>Telefone da Loja:</label>
                <input
                    type="text"
                    name="telephone"
                    value="{{ $setting->telephone }}"
                    placeholder="Ex: 11955555555"
                >
            </div>

            <button type="submit">Salvar alterações</button>

        </form>

    @else

        <form action="{{ route('setting.store') }}" method="POST">
            @csrf

            <div>
                <label>Nome da Loja:</label>
                <input
                    type="text"
                    name="name"
                    placeholder="Ex: Dimensiona"
                >
            </div>

            <div>
                <label>CNPJ:</label>
                <input
                    type="text"
                    name="cnpj"
                    placeholder="Ex: 000000000/0001-00"
                >
            </div>

            <div>
                <label>Email de Contato:</label>
                <input
                    type="text"
                    name="email"
                    placeholder="Ex: dimensiona@example.com"
                >
            </div>

            <div>
                <label>Telefone da Loja:</label>
                <input
                    type="text"
                    name="telephone"
                    placeholder="Ex: 11955555555"
                >
            </div>

            <button type="submit">Salvar</button>

        </form>

    @endif

    @if($errors->any())
        <div>
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

@endsection