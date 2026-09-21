<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // cria um pedido no banco
    public function store(Request $request)
    {
        $data = $request->validate([
            'cart_item_id' => ['required', 'integer', 'exists:cart_items,id'],
        ]);

        // verifica se o usuário está autenticado
        $user = auth()->user();
        // verifica se o carrinho do usuário existe
        $cart = $user->cart;

        // se não houver carrinho ou carrinho vazio, retorna um erro
        if (! $cart || $cart->cartItems->isEmpty()) {
            return back()->with('error', 'Carrinho vazio');
        }

        $cartItem = $cart->cartItems()
            ->with('product')
            ->find($data['cart_item_id']);

        if (! $cartItem instanceof CartItem) {
            return back()
                ->withErrors(['cart_item_id' => 'Selecione um produto do seu carrinho.'])
                ->withInput();
        }

        $order = DB::transaction(function () use ($user, $cartItem) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => $cartItem->quantity * $cartItem->product->price,
            ]);

            $order->orderItems()->create([
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
            ]);

            return $order;
        });

        return redirect()->route('orders.show', $order);
    }

    // exibe o pedido especifico
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    // adiciona um endereço ao pedido
    public function address(Request $request, Order $order)
    {
        $data = $request->validate([
            'address_id' => ['required', 'integer', 'exists:addresses,id'],
        ]);

        $address = Address::where('user_id', auth()->id())->findOrFail($data['address_id']);

        $order->update([
            'address_id' => $address->id,
        ]);

        return redirect()->route('orders.show', $order);
    }

    // exclui o pedido
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('products.index');
    }
}
