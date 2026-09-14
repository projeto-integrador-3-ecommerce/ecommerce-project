<?php

namespace App\Http\Controllers;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        return view('products.index', [
            'products' => $products
        ]);
    }

    public function show(Product $product){
        return view('products.show', [
            'product' => $product
        ]);
    }

    public function edit(Product $product){
        return view('products.edit', [
            'product' => $product
        ]);
    }

    public function update(Product $product, Request $req){
        $data = $req->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'preco' => ['required', 'numeric', 'min:0'],
            'estoque' => ['required', 'integer', 'min:0'],
            'cor' => ['required', 'string', 'max:255'],
            'tamanho' => ['required', 'string', 'max:255'],
            'material' => ['required', 'string', 'max:255'],
        ]);

        $product->update($data);

        return redirect()->route('products.show', $product);
    }

    public function create(){
        return view('products.create');
    }

    public function store(Request $req){
        $data = $req->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'color' => ['required', 'string', 'max:255'],
            'size' => ['required', 'string', 'max:255'],
            'material' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $product = Product::create($data);

        return redirect()->route('products.index', $product);
    }

}
