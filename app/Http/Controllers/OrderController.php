<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store()
    {
        $user = auth()->user();
        $cart = $user->cart;

        if(!$cart || $cart->cartItems->isEmpty()){
            return back()->with('error', 'Carrinho vazio');
        }
        $total = 0;

        foreach($cart->cartItems as $item){
            $total += $item->quantity * $item->product->price;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => $total
        ]);

        return redirect()->route('orders.show', $order);
    }

    public function show(Order $order){
        return view('orders.show', compact('order'));
    }

    public function destroy(Order $order){
        $order->delete();
        return redirect()->route('products.index');
    }
}