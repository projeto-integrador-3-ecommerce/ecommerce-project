<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $data['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity']
        ]);
    }

        return redirect()->route('cart.index');
    }

    public function destroy(CartItem $cartItem)
    {
        $cartItem->quantity -= 1;

        if($cartItem->quantity <= 0){
            $cartItem->delete();
        } else {
            $cartItem->save();
        }   

        return redirect()->route('cart.index');
    }

    public function add(CartItem $cartItem)
    {
        $cartItem->quantity += 1;
        $cartItem->save();

        return redirect()->route('cart.index');
    }
}
