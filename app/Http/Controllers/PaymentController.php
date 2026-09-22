<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();

        return view('payments.index', compact('payments'));
    }

    public function create(Order $order)
    {
        return view('payment.create', compact('order'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'method' => ['required', 'string', 'max:255'],
        ]);

        $order = Order::findOrFail($data['order_id']);

        Payment::create([
            'order_id' => $order->id,
            'status' => 'Realizado',
            'method' => $data['method'],
        ]);

        $order->update(['status' => 'Realizado']);

        return redirect()->route('orders.show', $order);
    }
}
