<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // exibe todas as categorias
    public function index(){
        $categories = Category::all();
        return view('categories.index', [
            'categories' => $categories
        ]);
    }

    // exibe a tela de criar categoria
    public function create(){
        return view('categories.create');
    }

    // cria a categoria no banco
    public function store(Request $req){
        $data = $req->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category = Category::create($data);

        return redirect()->route('categories.show', $category);
    }

    // exclui a categoria
    public function destroy(Category $category){
        $category->delete();

        return redirect()->route('categories.index');
    }
}