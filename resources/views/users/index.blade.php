@extends('layouts.storefront', ['title' => 'Usuários cadastrados'])

@section('content')
<section class="page-head"><div class="container"><span class="eyebrow">Administração</span><h1>Usuários cadastrados</h1><p>{{ $users->total() }} contas registradas na loja.</p></div></section>
<section class="container pb-20">
    @if ($errors->has('user'))
        <p class="status-pill" role="alert">{{ $errors->first('user') }}</p>
    @endif
    @forelse ($users as $user)
        <article class="info-card mb-4">
            <div class="section__head">
                <div><h2>{{ $user->name }}</h2><p>{{ $user->email }}</p><p>Cadastro: {{ $user->created_at->format('d/m/Y') }}</p></div>
                <span class="status-pill">{{ $user->is_admin ? 'Administrador' : 'Cliente' }}</span>
            </div>
            @unless ($user->is_admin)
                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Remover este usuário?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn--outline mt-4" type="submit">Remover usuário</button>
                </form>
            @endunless
        </article>
    @empty
        <div class="empty-state"><h2>Nenhum usuário cadastrado</h2></div>
    @endforelse
    <div class="mt-6">{{ $users->links() }}</div>
</section>
@endsection
