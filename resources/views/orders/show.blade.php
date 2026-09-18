@extends('layouts.app')

@section('content')

    <h1>Finalizar Compra: Id do pedido #{{$order->id}}</h1>
    <p>Status: {{$order->status}}</p>
    <p>Total: R${{$order->total}}</p>

@endsection
