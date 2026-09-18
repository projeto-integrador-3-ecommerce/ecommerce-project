<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;

class OrderController extends Controller
{
    public function store()
    {
        $user = auth()->user();
        $cart = $user->cart();

        if(!$cart || $cart->cartItems->isEmpty()){
            return back()->with('error', 'Carrinho vazio');
        }
        $total = 0;

        foreach($cart->cartItems as $item){
            $total += $item->quantity * $item->product->price;
        }

        $order = OrdeR::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => $total
        ]);

        return redirect()->route('orders.show', $order);
    }
}