@extends('layouts.app')

@section('content')

    <h1>{{ auth()->user()->is_admin ? 'Endereços cadastrados' : 'Meus endereços' }}</h1>

    @foreach($addresses as $address)

        <div>
            <p>
                {{ $address->street }},
                {{ $address->number }},
                {{ $address->city }} -
                {{ $address->state }}
            </p>

            @if(auth()->user()->is_admin)
                <p>Usuário: {{ $address->user->name }} ({{ $address->user->email }})</p>
            @else
                <a href="{{ route('addresses.edit', $address) }}">Editar Endereço</a>

                <form action="{{ route('addresses.destroy', $address) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit">Remover Endereço</button>
                </form>
            @endif
        </div>

    @endforeach

    @unless(auth()->user()->is_admin)
        <a href="{{ route('addresses.create') }}">Cadastrar Endereço</a>
    @endunless

    {{ $addresses->links() }}

@endsection