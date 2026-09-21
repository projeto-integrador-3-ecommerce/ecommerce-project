@extends('layouts.app')

@section('content')

    <h1>Escolha um endereço</h1>

    @foreach($addresses as $address)

        <div>
            <input
                type="radio"
                name="address_id"
                value="{{ $address->id }}"
                id="address-{{ $address->id }}"
                form="address-selection"
            >

            <label for="address-{{ $address->id }}">
                {{ $address->street }},
                {{ $address->number }},
                {{ $address->city }} -
                {{ $address->state }}
            </label>

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

    <form
        id="address-selection"
        action="{{ route('orders.address', $order) }}"
        method="POST"
    >
        @csrf

        <button type="submit">
            Usar este endereço
        </button>
    </form>

    <a href="/addresses/create">
        <button type="button">Cadastrar Endereço</button>
    </a>

@endsection