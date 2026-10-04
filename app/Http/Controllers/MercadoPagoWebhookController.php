<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MercadoPagoClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class MercadoPagoWebhookController extends Controller
{
    public function __invoke(Request $request, MercadoPagoClient $mercadoPago): JsonResponse
    {
        $paymentId = (string) ($request->input('data.id') ?? $request->query('data.id') ?? '');

        if ($paymentId === '' || ! ctype_digit($paymentId)) {
            return response()->json(['message' => 'Invalid payment identifier.'], 400);
        }

        if (! $this->hasValidSignature($request, $paymentId)) {
            return response()->json(['message' => 'Invalid notification signature.'], 401);
        }

        try {
            $remotePayment = $mercadoPago->fetchPayment($paymentId);
        } catch (ConnectionException|RequestException|RuntimeException $exception) {
            report($exception);

            return response()->json(['message' => 'Payment provider temporarily unavailable.'], 503);
        }

        $orderId = filter_var($remotePayment['external_reference'] ?? null, FILTER_VALIDATE_INT);

        if (! is_int($orderId)) {
            return response()->json(['received' => true]);
        }

        $order = Order::query()->with('payment')->find($orderId);
        $payment = $order?->payment;
        $remoteAmount = number_format((float) ($remotePayment['transaction_amount'] ?? 0), 2, '.', '');
        $orderAmount = $order === null ? null : number_format((float) $order->total, 2, '.', '');

        if (
            $order === null
            || $payment === null
            || ($remotePayment['currency_id'] ?? null) !== 'BRL'
            || $remoteAmount !== $orderAmount
            || ! is_string($remotePayment['status'] ?? null)
        ) {
            return response()->json(['received' => true]);
        }

        $remoteStatus = $remotePayment['status'];
        $payment->update([
            'external_payment_id' => (string) ($remotePayment['id'] ?? $paymentId),
            'status' => $remoteStatus,
            'status_detail' => is_string($remotePayment['status_detail'] ?? null)
                ? $remotePayment['status_detail']
                : null,
            'method' => 'mercado_pago',
        ]);

        if ($remoteStatus === 'approved' && $order->status === Order::STATUS_AWAITING_PAYMENT) {
            $order->update(['status' => Order::STATUS_PAYMENT_CONFIRMED]);
        } elseif ($remoteStatus === 'cancelled' && $order->status === Order::STATUS_AWAITING_PAYMENT) {
            $order->update(['status' => Order::STATUS_CANCELED]);
        }

        return response()->json(['received' => true]);
    }

    private function hasValidSignature(Request $request, string $paymentId): bool
    {
        $secret = config('services.mercadopago.webhook_secret');
        $requestId = (string) $request->header('x-request-id', '');
        $signatureParts = [];

        foreach (explode(',', (string) $request->header('x-signature', '')) as $part) {
            [$key, $value] = array_pad(explode('=', trim($part), 2), 2, '');
            $signatureParts[$key] = $value;
        }

        if (
            ! is_string($secret)
            || $secret === ''
            || $requestId === ''
            || ! ctype_digit($signatureParts['ts'] ?? '')
            || ! isset($signatureParts['v1'])
        ) {
            return false;
        }

        $manifest = 'id:'.strtolower($paymentId)
            .';request-id:'.$requestId
            .';ts:'.$signatureParts['ts'].';';
        $expectedSignature = hash_hmac('sha256', $manifest, $secret);

        return hash_equals($expectedSignature, $signatureParts['v1']);
    }
}
