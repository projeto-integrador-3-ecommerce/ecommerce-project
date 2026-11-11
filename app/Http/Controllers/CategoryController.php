<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    // exibe todas as categorias
    public function index()
    {
        $categories = Category::withCount('products')->get();

        return view('categories.index', [
            'categories' => $categories,
        ]);
    }

    // exibe a tela de criar categoria
    public function create(): View
    {
        return view('categories.create');
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', [
            'category' => $category,
        ]);
    }

    // cria a categoria no banco
    public function store(Request $req): RedirectResponse
    {
        $data = $req->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Categoria cadastrada com sucesso.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    // exclui a categoria
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return redirect()->route('categories.index')
                ->withErrors(['category' => 'Remova ou mova os produtos desta categoria antes de excluí-la.']);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoria removida com sucesso.');
    }
}
