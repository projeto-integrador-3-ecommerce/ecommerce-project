<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return view('categories.index', [
            'categories' => $categories
        ]);
    }

    public function show(Category $category){
        return view('categories.show', [
            'category' => $category
        ]);
    }

    public function create(){
        return view('categories.create');
    }

    public function store(Request $req){
        $data = $req->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category = Category::create($data);

        return redirect()->route('categories.show', $category);
    }

    public function edit(Category $category){
        return view('categories.edit', [
            'category' => $category
        ]);
    }

    public function update(Category $category, Request $req){
        $data = $req->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category->update($data);

        return redirect()->route('categories.show', $category);
    }

    public function destroy(Category $category){
        $category->delete();

        return redirect()->route('categories.index');
    }
}