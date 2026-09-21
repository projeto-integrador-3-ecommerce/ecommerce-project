<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // cria um pedido no banco
    public function store()
    {
        // verifica se o usuário está autenticado
        $user = auth()->user();
        // verifica se o carrinho do usuário existe
        $cart = $user->cart;

        // se não houver carrinho ou carrinho vazio, retorna um erro
        if(!$cart || $cart->cartItems->isEmpty()){
            return back()->with('error', 'Carrinho vazio');
        }
        $total = 0;

        // percorre os itens do carrinho e calcula o total
        foreach($cart->cartItems as $item){
            $total += $item->quantity * $item->product->price;
        }

        // cria o pedido
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => $total
        ]);

        return redirect()->route('orders.show', $order);
    }

    // exibe o pedido especifico
    public function show(Order $order){
        return view('orders.show', compact('order'));
    }

    // exclui o pedido
    public function destroy(Order $order){
        $order->delete();
        return redirect()->route('products.index');
    }
}