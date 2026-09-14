@extends('layouts.app')
<!-- herdando html e css do layout -->
@section('content')

    <h1>Products</h1>

    <ul>
        @foreach($products as $product)
            <li>{{ $product->name }} - ${{ $product->price }}</li>
            <form action="/products/{{ $product->id }}" method="POST">
                @csrf
                <button type="submit">Excluir Produto</button>
            </form>
        @endforeach
    </ul>

    <button><a href="/products/create">Cadastrar Produto</a></button>

@endsection