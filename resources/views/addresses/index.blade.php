@extends('layouts.app')

@section('content')

    <h1>Meus endereços</h1>

    @foreach($addresses as $address)

        <div>
            <p>
                {{ $address->street }},
                {{ $address->number }},
                {{ $address->city }} -
                {{ $address->state }}
            </p>

            <a href="/addresses/{{ $address->id }}/edit">
                <button type="button">Editar Endereço</button>
            </a>

            <form action="{{ route('addresses.destroy', $address) }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit">Remover Endereço</button>
            </form>
        </div>

    @endforeach

    <a href="{{ route('addresses.create') }}">
        <button type="button">Cadastrar Endereço</button>
    </a>

@endsection