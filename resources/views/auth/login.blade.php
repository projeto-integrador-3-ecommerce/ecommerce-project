@extends('layouts.app')

@section('content')

    <h1>Login</h1>

    <form method="POST" action="{{ route('login.store') }}">

        @csrf

        <label for="email">Email</label>
        <input type="email" id="email" name="email">

        <label for="password">Password</label>
        <input type="password" id="password" name="password">

        <button type="submit">Login</button>
        <button><a href="/auth/register">Register</a></button>
        <button><a href="/auth/password/reset">Forgot password?</a></button>

    </form>

@endsection