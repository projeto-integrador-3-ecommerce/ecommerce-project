<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    // cria um pedido no banco
    public function store(Request $request)
    {
        $data = $request->validate([
            'cart_item_ids' => ['required', 'array', 'min:1'],
            'cart_item_ids.*' => ['required', 'integer', 'distinct', 'exists:cart_items,id'],
        ]);

        $user = $request->user();
        $cart = $user->cart;

        if (! $cart) {
            return back()->withErrors(['cart_item_ids' => 'Seu carrinho está vazio.']);
        }

        $order = DB::transaction(function () use ($user, $cart, $data): Order {
            $cartItems = $cart->cartItems()
                ->with('product')
                ->whereIn('id', $data['cart_item_ids'])
                ->lockForUpdate()
                ->get();

            if ($cartItems->count() !== count($data['cart_item_ids'])) {
                throw ValidationException::withMessages([
                    'cart_item_ids' => 'Selecione apenas itens do seu carrinho.',
                ]);
            }

            $totalCents = 0;

            foreach ($cartItems as $cartItem) {
                $totalCents += (int) round((float) $cartItem->product->price * 100) * $cartItem->quantity;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'status' => Order::STATUS_AWAITING_PAYMENT,
                'total' => number_format($totalCents / 100, 2, '.', ''),
            ]);

            foreach ($cartItems as $cartItem) {
                $order->orderItems()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                ]);
            }

            $cart->cartItems()->whereIn('id', $cartItems->modelKeys())->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order);
    }

    // exibe o pedido especifico
    public function show(Order $order)
    {
        Gate::authorize('view', $order);

        $order->load(['payment', 'address', 'orderItems.product']);

        return view('orders.show', compact('order'));
    }

    // adiciona um endereço ao pedido
    public function address(Request $request, Order $order)
    {
        Gate::authorize('addAddress', $order);

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
        Gate::authorize('delete', $order);

        $order->update(['status' => Order::STATUS_CANCELED]);

        return redirect()->route('orders.show', $order);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        Gate::authorize('updateStatus', $order);

        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUS_LABELS))],
        ]);

        $order->update(['status' => $data['status']]);

        return redirect()->route('orders.show', $order);
    }

    public function index(Request $request): View
    {
        $query = Order::with(['user', 'address'])->latest();

        if (! $request->user()->is_admin) {
            $query->where('user_id', $request->user()->id);
        }

        $orders = $query->paginate(15);

        return view('dashboard.index', [
            'orders' => $orders,
        ]);
    }
}
