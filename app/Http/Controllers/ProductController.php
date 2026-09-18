<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // função que exibe a lista de produtos
    public function index(){
        $products = Product::all();
        return view('products.index', [
            'products' => $products
        ]);
    }

    // função que exibe apenas 1 produto
    public function show(Product $product){
        return view('products.show', [
            'product' => $product
        ]);
    }

    // função que retorna a view com o forms pra editar o produto
    public function edit(Product $product){
        return view('products.edit', [
            'product' => $product
        ]);
    }

    // função que atualiza a edição no banco
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

    // função que exibe o forms de criar produto
    public function create(){
        $categories = Category::all();
        return view('products.create', [
            'categories' => $categories
        ]);
    }

    // função que cria o produto no banco
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

    // função que deleta um produto
    public function destroy(Product $product){
        $product->delete();

        return redirect()->route('products.index');
    }

}
