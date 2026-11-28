@extends('layouts.storefront', ['title' => 'Recuperar senha'])
@section('content')
<div class="mx-auto max-w-xl rounded-3xl border border-aqua bg-white p-6 shadow-sm sm:p-10">
    <div class="text-center">
        <x-ui.logo size="lg" class="mx-auto" />
        <h1 class="mt-5 text-2xl font-semibold text-navy">Recuperar senha</h1>
        <p class="mt-3 text-sm text-muted">Enviaremos instruções para o e-mail da sua conta.</p>
    </div>
    @if ($errors->any())
        <p role="alert" class="mt-6 rounded-2xl bg-red/10 px-4 py-3 text-center text-sm font-semibold text-red">{{ $errors->first() }}</p>
    @endif
    @if (session('status'))
        <p role="status" class="mt-6 rounded-2xl bg-teal/10 px-4 py-3 text-center text-sm font-semibold text-teal">{{ session('status') }}</p>
    @endif
    <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">@csrf
        <x-ui.input label="E-mail" type="email" name="email" maxlength="255" value="{{ old('email') }}" autocomplete="email" required />
        <x-ui.button type="submit" size="lg" class="w-full">Enviar instruções</x-ui.button>
    </form>
    <p class="mt-6 text-center text-xs text-muted"><a href="{{ route('login') }}" class="rounded-full px-2 font-semibold text-navy underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Voltar para o login</a></p>
</div>
@endsection
