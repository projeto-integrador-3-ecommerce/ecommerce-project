@extends('layouts.app')

@section('content')

    <h1>Informações da Loja:</h1>
    <form action="{{ route('setting.update', $setting) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Nome da Loja:</label>
            <input type="text" name="name" maxlength="120" value="{{ old('name', $setting->name) }}" required>
        </div>
        <div>
            <label>CNPJ:</label>
            <input type="text" name="cnpj" maxlength="18" value="{{ old('cnpj', $setting->cnpj) }}" required>
        </div>
        <div>
            <label>Email de Contato:</label>
            <input type="email" name="email" maxlength="255" value="{{ old('email', $setting->email) }}" required>
        </div>
        <div>
            <label>Telefone da Loja:</label>
            <input type="tel" name="telephone" maxlength="20" value="{{ old('telephone', $setting->telephone) }}" required>
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