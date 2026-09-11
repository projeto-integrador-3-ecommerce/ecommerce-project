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

    </form>

@endsection