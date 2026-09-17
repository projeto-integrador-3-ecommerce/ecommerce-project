@extends('layouts.app')

@section('content')

    <h1>{{$product->name}}</h1>
    <p>Price: ${{$product->price}}</p>
    <p>Description: {{$product->description}}</p>

    <button><a href="{{ route('products.edit', $product)}}">Editar Produto</a></button>

@endsection