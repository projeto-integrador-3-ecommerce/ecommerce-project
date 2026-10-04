<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // função que exibe a lista de produtos
    public function index()
    {
        $products = Product::all();

        return view('products.index', [
            'products' => $products,
        ]);
    }

    // função que exibe apenas 1 produto
    public function show(Product $product)
    {
        return view('products.show', [
            'product' => $product,
        ]);
    }

    // função que retorna a view com o forms pra editar o produto
    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product,
            'categories' => Category::all(),
        ]);
    }

    // função que atualiza a edição no banco
    public function update(Product $product, Request $req)
    {
        $data = $req->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:999999'],
            'color' => ['required', 'string', 'max:40'],
            'size' => ['required', 'string', 'max:40'],
            'material' => ['required', 'string', 'max:80'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($req->hasFile('image')) {
            $imagePath = $req->file('image')->store('products', 'public');

            if ($imagePath === false) {
                return back()->withErrors(['image' => 'Não foi possível salvar a imagem.'])->withInput();
            }

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('products.show', $product);
    }

    // função que exibe o forms de criar produto
    public function create()
    {
        $categories = Category::all();

        return view('products.create', [
            'categories' => $categories,
        ]);
    }

    // função que cria o produto no banco
    public function store(Request $req)
    {
        $data = $req->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:999999'],
            'color' => ['required', 'string', 'max:40'],
            'size' => ['required', 'string', 'max:40'],
            'material' => ['required', 'string', 'max:80'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($req->hasFile('image')) {
            $imagePath = $req->file('image')->store('products', 'public');

            if ($imagePath === false) {
                return back()->withErrors(['image' => 'Não foi possível salvar a imagem.'])->withInput();
            }

            $data['image'] = $imagePath;
        }

        $product = Product::create($data);

        return redirect()->route('products.index');
    }

    // função que deleta um produto
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index');
    }
}
