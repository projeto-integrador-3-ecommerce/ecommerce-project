<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\MercadoPagoClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use RuntimeException;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::all();

        return view('payments.index', compact('payments'));
    }

    public function create(Order $order): View
    {
        Gate::authorize('pay', $order);

        return view('payment.create', compact('order'));
    }

    public function store(Order $order, MercadoPagoClient $mercadoPago): RedirectResponse
    {
        Gate::authorize('pay', $order);

        $payment = $order->payment()->firstOrCreate([], [
            'status' => 'pending',
            'method' => 'mercado_pago',
        ]);

        try {
            $checkoutUrl = $mercadoPago->createCheckoutUrl($order, $payment);
        } catch (ConnectionException|RequestException|RuntimeException $exception) {
            report($exception);

            return back()->withErrors([
                'payment' => 'Não foi possível iniciar o pagamento. Tente novamente em instantes.',
            ]);
        }

        return redirect()->away($checkoutUrl);
    }

    public function returnFromCheckout(Order $order): RedirectResponse
    {
        Gate::authorize('view', $order);

        return redirect()->route('orders.show', $order)->with(
            'status',
            'Retorno recebido. A confirmação será atualizada após a validação do Mercado Pago.'
        );
    }
}
