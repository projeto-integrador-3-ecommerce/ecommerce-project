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
}