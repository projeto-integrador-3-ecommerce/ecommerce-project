@extends('layouts.app')

@section('content')

    <h1>Informações da Loja:</h1>
    <form action="{{ route('setting.update', $setting) }}" method="POST">
        @csrf

        <div>
            <label>Nome da Loja:</label>
            <input type="text" name="name" value="{{ $setting->name }}">
        </div>
        <div>
            <label>CNPJ:</label>
            <input type="text" name="cnpj" value="{{ $setting->cnpj }}">
        </div>
        <div>
            <label>Email de Contato:</label>
            <input type="text" name="email" value="{{ $setting->email }}">
        </div>
        <div>
            <label>Telefone da Loja:</label>
            <input type="text" name="telephone" value="{{ $setting->telephone }}">
        </div>

        <button type="submit">Atualizar</button>
    </form>

    @if($errors->any())
        <div>
            <p>
                {{ $errors->first() }}
            </p>
        </div>
    @endif

@endsection