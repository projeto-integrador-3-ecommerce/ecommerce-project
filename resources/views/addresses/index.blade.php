@extends('layouts.app')

@section('content')

    <h1>Seus Endereços</h1>
    @foreach($addresses as $address)
        <div>
            <h2>{{ $address->street }}, {{ $address->number }}</h2>
            <p>{{ $address->city }}, {{ $address->state }}, {{ $address->country }}</p>
        </div>
        <button><a href="">Editar Endereço</a></button>
        <button><a href="">Remover Endereço</a></button>
    @endforeach

    <button><a href="/addresses/create">Cadastrar Endereço</a></button>

@endsection