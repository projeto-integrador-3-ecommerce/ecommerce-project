<?php

namespace App\Http\Controllers;

use App\Models\Cart;

class CartController extends Controller
{
    // exibe do carrinho do usuario
    public function index()
    {
        // carrinho existe? se nao existe, verifica se o usuario autenticado é o mesmo e cria
        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        return view('carts.index', [
            'cart' => $cart,
        ]);
    }
}
