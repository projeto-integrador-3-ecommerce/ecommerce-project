<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MercadoPagoClient
{
    public function createCheckoutUrl(Order $order, Payment $payment): string
    {
        if ($payment->checkout_url) {
            return $payment->checkout_url;
        }

        $accessToken = config('services.mercadopago.access_token');

        if (! is_string($accessToken) || $accessToken === '') {
            throw new RuntimeException('Mercado Pago access token is not configured.');
        }

        $order->loadMissing('user');

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->connectTimeout(3)
            ->timeout(15)
            ->withHeaders([
                'X-Idempotency-Key' => 'order-'.$order->id.'-payment-'.$payment->id,
            ])
            ->post('https://api.mercadopago.com/checkout/preferences', [
                'items' => [[
                    'title' => 'Pedido #'.$order->id,
                    'quantity' => 1,
                    'unit_price' => (float) $order->total,
                    'currency_id' => 'BRL',
                ]],
                'payer' => ['email' => $order->user->email],
                'external_reference' => (string) $order->id,
                'back_urls' => [
                    'success' => route('payment.return', $order),
                    'pending' => route('payment.return', $order),
                    'failure' => route('payment.return', $order),
                ],
                'auto_return' => 'approved',
                'notification_url' => route('mercadopago.webhook'),
            ])
            ->throw();

        $preference = $response->json();
        $preferenceId = $preference['id'] ?? null;
        $checkoutUrl = $preference['init_point'] ?? null;

        if (! is_string($preferenceId) || ! is_string($checkoutUrl)) {
            throw new RuntimeException('Mercado Pago returned an incomplete checkout preference.');
        }

        $payment->update([
            'preference_id' => $preferenceId,
            'checkout_url' => $checkoutUrl,
            'method' => 'mercado_pago',
        ]);

        return $checkoutUrl;
    }

    /** @return array<string, mixed> */
    public function fetchPayment(string $paymentId): array
    {
        $accessToken = config('services.mercadopago.access_token');

        if (! is_string($accessToken) || $accessToken === '') {
            throw new RuntimeException('Mercado Pago access token is not configured.');
        }

        $payment = Http::withToken($accessToken)
            ->acceptJson()
            ->connectTimeout(3)
            ->timeout(10)
            ->get('https://api.mercadopago.com/v1/payments/'.$paymentId)
            ->throw()
            ->json();

        if (! is_array($payment)) {
            throw new RuntimeException('Mercado Pago returned an invalid payment response.');
        }

        return $payment;
    }
}
