@extends('layouts.storefront', ['title' => 'Novidades'])

@section('content')
<section><div><span>Acabaram de chegar</span><h1>Novidades</h1><p>Confira os produtos mais recentes do catálogo.</p></div></section>
<section>
    <div>
        @forelse ($products as $product)
            <x-storefront.product-card :product="$product" />
        @empty
            <div><h2>Nenhuma novidade por enquanto</h2><p>Novos produtos aparecerão aqui assim que forem cadastrados.</p><a href="{{ route('products.index') }}">Ver catálogo</a></div>
        @endforelse
    </div>
</section>
@endsection
