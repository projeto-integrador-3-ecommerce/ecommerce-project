<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    // cria um item no carrinho
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        // verifica se existe o carrinho do usuario, s enão, cria
        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        // verifica se o produto já é um item e se já existe no carrinho
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $data['product_id'])
            ->first();

        // se existir, aumenta a quantidade enviada
        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } else {
            // se não, cria um novo item
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity']
        ]);
    }

        return redirect()->route('cart.index');
    }

    // exclui um item do produto do carrinho
    public function destroy(CartItem $cartItem)
    {
        $cartItem->quantity -= 1;

        // se a quantidasde for 0, exclui o produto
        if($cartItem->quantity <= 0){
            $cartItem->delete();
        } else {
            $cartItem->save();
        }   

        return redirect()->route('cart.index');
    }

    // adiciona um item do produto no carrinho
    public function add(CartItem $cartItem)
    {
        $cartItem->quantity += 1;
        $cartItem->save();

        return redirect()->route('cart.index');
    }
}
