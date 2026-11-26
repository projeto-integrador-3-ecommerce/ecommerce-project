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

        $pixCode = $this->demoPixCode($order);

        return view('payment.create', compact('order', 'pixCode'));
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

    /**
     * Gera um código Pix "copia e cola" apenas ilustrativo (chave inexistente, sem cobrança real).
     */
    private function demoPixCode(Order $order): string
    {
        $field = fn (string $id, string $value): string => $id.str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT).$value;

        $payload = $field('00', '01')
            .$field('26', $field('00', 'br.gov.bcb.pix').$field('01', 'simulacao@dimensiona.example'))
            .$field('52', '0000')
            .$field('53', '986')
            .$field('54', number_format((float) $order->total, 2, '.', ''))
            .$field('58', 'BR')
            .$field('59', 'DIMENSIONA')
            .$field('60', 'SAO PAULO')
            .$field('62', $field('05', 'DIM'.str_pad((string) $order->id, 6, '0', STR_PAD_LEFT)))
            .'6304';

        return $payload.$this->crc16($payload);
    }

    private function crc16(string $payload): string
    {
        $crc = 0xFFFF;

        foreach (str_split($payload) as $char) {
            $crc ^= ord($char) << 8;

            for ($bit = 0; $bit < 8; $bit++) {
                $crc = ($crc & 0x8000) ? (($crc << 1) ^ 0x1021) : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }
}
