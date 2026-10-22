<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function home(): View
    {
        return view('storefront.home', [
            'categories' => Category::withCount('products')->get(),
            'products' => Product::with('category')->latest()->take(8)->get(),
        ]);
    }

    public function novelties(): View
    {
        return view('storefront.novelties', [
            'products' => Product::with('category')->latest()->take(12)->get(),
        ]);
    }

    public function search(Request $request): View
    {
        $term = trim((string) $request->query('q', ''));
        $products = Product::with('category')
            ->when($term !== '', function ($query) use ($term): void {
                $query->where(function ($query) use ($term): void {
                    $query->where('name', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->get();

        return view('storefront.search', [
            'categories' => Category::withCount('products')->get(),
            'products' => $products,
            'term' => $term,
        ]);
    }

    public function about(): View
    {
        return view('storefront.about');
    }
}
