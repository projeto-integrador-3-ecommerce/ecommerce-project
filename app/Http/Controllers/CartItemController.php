<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CartItemController extends Controller
{
    // cria um item no carrinho
    public function store(Request $request): JsonResponse|RedirectResponse
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
                'quantity' => $data['quantity'],
            ]);
        }

        return $this->respondToCartMutation($request, 'Produto adicionado ao carrinho.', $cartItem);
    }

    // exclui um item do produto do carrinho
    public function destroy(Request $request, CartItem $cartItem): JsonResponse|RedirectResponse
    {
        Gate::authorize('delete', $cartItem);

        $cartItem->quantity -= 1;

        // se a quantidasde for 0, exclui o produto
        if ($cartItem->quantity <= 0) {
            $cartItem->delete();
        } else {
            $cartItem->save();
        }

        return $this->respondToCartMutation(
            $request,
            $cartItem->exists ? 'Quantidade atualizada.' : 'Produto removido do carrinho.',
            $cartItem,
        );
    }

    // adiciona um item do produto no carrinho
    public function add(Request $request, CartItem $cartItem): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $cartItem);

        $cartItem->quantity += 1;
        $cartItem->save();

        return $this->respondToCartMutation($request, 'Quantidade atualizada.', $cartItem);
    }

    private function respondToCartMutation(Request $request, string $message, ?CartItem $item): JsonResponse|RedirectResponse
    {
        if (! $request->expectsJson()) {
            return redirect()->route('cart.index')->with('success', $message);
        }

        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        $cart->load('cartItems.product');

        return response()->json([
            'message' => $message,
            'cart_count' => $cart->cartItems->sum('quantity'),
            'item_id' => $item?->id,
            'item_quantity' => $item?->exists ? $item->quantity : null,
            'item_total' => $item?->exists ? round((float) $item->product->price * $item->quantity, 2) : null,
        ]);
    }
}
