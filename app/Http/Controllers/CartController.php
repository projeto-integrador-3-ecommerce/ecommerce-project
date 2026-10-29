<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    // exibe do carrinho do usuario
    public function index(Request $request): View
    {
        if (! $request->user()) {
            return view('carts.index', [
                'cart' => null,
            ]);
        }

        // carrinho existe? se nao existe, verifica se o usuario autenticado é o mesmo e cria
        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);
        $cart->load('cartItems.product');

        return view('carts.index', [
            'cart' => $cart,
        ]);
    }
}
