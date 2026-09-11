@extends('layouts.app')

@section('content')

    <h1>Create Account</h1>
    <form action="{{ route('register.store') }}" method="POST">
        @csrf

        <div>
            <label>Name</label>
            <input type="text" name="name" placeholder="Enter your name">
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email">
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password">
        </div>
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" placeholder="Confirm your password">
        </div>

        <button type="submit"><a href="/auth/register">Register</a></button>
        <button><a href="/auth/login">Do you have an account? Login</a></button>
    </form>

    @if($errors->any())
        <div>
            {{ $errors->first() }}
        </div>
   @endif
   
@endsection
