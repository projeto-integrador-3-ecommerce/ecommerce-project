@extends('layouts.storefront', ['title' => 'Usuários cadastrados'])

@section('content')
<section><div><span>Administração</span><h1>Usuários cadastrados</h1><p>{{ $users->total() }} contas registradas na loja.</p></div></section>
<section>
    @if ($errors->has('user'))
        <p role="alert">{{ $errors->first('user') }}</p>
    @endif
    @forelse ($users as $user)
        <article>
            <div>
                <div><h2>{{ $user->name }}</h2><p>{{ $user->email }}</p><p>Cadastro: {{ $user->created_at->format('d/m/Y') }}</p></div>
                <span>{{ $user->is_admin ? 'Administrador' : 'Cliente' }}</span>
            </div>
            @unless ($user->is_admin)
                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Remover este usuário?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Remover usuário</button>
                </form>
            @endunless
        </article>
    @empty
        <div><h2>Nenhum usuário cadastrado</h2></div>
    @endforelse
    <div>{{ $users->links() }}</div>
</section>
@endsection
