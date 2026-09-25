@extends('layouts.app')

@section('content')

    <h1>Escolha um endereço</h1>

    @if($addresses->isNotEmpty())
        <form action="{{ route('orders.address', $order) }}" method="POST">
            @csrf

            @foreach($addresses as $address)
                <div>
                    <input
                        type="radio"
                        name="address_id"
                        value="{{ $address->id }}"
                        id="address-{{ $address->id }}"
                        required
                    >

                    <label for="address-{{ $address->id }}">
                        {{ $address->street }},
                        {{ $address->number }},
                        {{ $address->city }} -
                        {{ $address->state }}
                    </label>
                </div>
            @endforeach

            <button type="submit">Usar este endereço</button>
        </form>
    @else
        <p>Nenhum endereço cadastrado.</p>
    @endif

    <a href="{{ route('addresses.create') }}">
        <button type="button">Cadastrar Endereço</button>
    </a>

    <a href="{{ route('orders.show', $order) }}">Voltar ao pedido</a>

@endsection