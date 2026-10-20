@extends('layouts.app')

@section('content')

    <h1>Usuários Dimensiona</h1>
    <section id="users">
        @foreach($users as $user)
            <div>
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->email }}</p>
            </div>
            <form action="{{ route('users.destroy', $user)}}" method="POST">
                @csrf
                @method('DELETE')

                <button type="SUBMIT">Deletar Usuário</button>
            </form>
        @endforeach
    </section>
@endsection