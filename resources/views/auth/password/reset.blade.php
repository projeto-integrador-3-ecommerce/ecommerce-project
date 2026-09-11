@extends('layouts.app')

@section('content')

    <h1>Forgot Password?</h1>

    <form method="POST" action="{{ route('password.reset') }}">
        @csrf

        <div>
            <label>Email</label>
            <input type="text" name="email" placeholder="Enter your email" required>
        </div>

        <button type="submit">Send password reset link</button>

    </form>

    @if($errors->any())
        <div>
            {{ $errors->first()}}
        </div>
    @endif

    @if(session('status'))
        <div>
            {{ session('status') }}
        </div>
    @endif


@endsection