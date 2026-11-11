<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_path' => ['nullable', 'string', 'max:255', 'regex:/^supabase:categories\/[a-f0-9-]+\.(?:jpe?g|png|webp)$/i'],
        ]);

        if (! empty($data['image_path'])) {
            $data['image'] = $data['image_path'];
        } elseif ($req->hasFile('image')) {
            $imagePath = $req->file('image')->store('categories', 'public');

            if ($imagePath === false) {
                return back()->withErrors(['image' => 'Não foi possível salvar a imagem.'])->withInput();
            }

            $data['image'] = $imagePath;
        }

        unset($data['image_path']);

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Categoria cadastrada com sucesso.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_path' => ['nullable', 'string', 'max:255', 'regex:/^supabase:categories\/[a-f0-9-]+\.(?:jpe?g|png|webp)$/i'],
        ]);

        if (! empty($data['image_path'])) {
            if ($category->image && ! str_starts_with($category->image, 'supabase:')) {
                Storage::disk('public')->delete($category->image);
            }

            $data['image'] = $data['image_path'];
        } elseif ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');

            if ($imagePath === false) {
                return back()->withErrors(['image' => 'Não foi possível salvar a imagem.'])->withInput();
            }

            if ($category->image && ! str_starts_with($category->image, 'supabase:')) {
                Storage::disk('public')->delete($category->image);
            }

            $data['image'] = $imagePath;
        }

        unset($data['image_path']);

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

        if ($category->image && ! str_starts_with($category->image, 'supabase:')) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoria removida com sucesso.');
    }
}
