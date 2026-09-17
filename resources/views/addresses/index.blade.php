@extends('layouts.app')

@section('content')

    <h1>Seus Endereços</h1>
    @foreach($addresses as $address)
        <div>
            <h2>{{ $address->street }}, {{ $address->number }}</h2>
            <p>{{ $address->city }}, {{ $address->state }}, {{ $address->country }}</p>
            <p>{{ $address->complement }}</p>
        </div>
        <button><a href="/addresses/{{ $address->id }}/edit">Editar Endereço</a></button>
        <form action="{{ route('addresses.destroy', $address) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Remover Endereço</button>
        </form>
    @endforeach

    <button><a href="/addresses/create">Cadastrar Endereço</a></button>

@endsection