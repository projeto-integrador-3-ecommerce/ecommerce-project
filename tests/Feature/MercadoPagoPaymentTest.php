<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MercadoPagoPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_is_redirected_to_hosted_checkout(): void
    {
        config(['services.mercadopago.access_token' => 'test-access-token']);
        Http::preventStrayRequests();
        Http::fake([
            'https://api.mercadopago.com/checkout/preferences' => Http::response([
                'id' => 'preference-123',
                'init_point' => 'https://www.mercadopago.com/checkout/preference-123',
            ], 201),
        ]);
        $user = User::factory()->create();
        $order = $this->createPayableOrder($user);

        $response = $this->actingAs($user)->post(route('payment.store', $order));

        $response->assertRedirect('https://www.mercadopago.com/checkout/preference-123');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_AWAITING_PAYMENT,
        ]);
        $payment = Payment::query()->where('order_id', $order->id)->firstOrFail();
        $this->assertSame('preference-123', $payment->preference_id);

        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://api.mercadopago.com/checkout/preferences'
            && $request['external_reference'] === (string) $order->id
            && $request->hasHeader('Authorization', 'Bearer test-access-token')
        );
    }

    public function test_signed_approved_webhook_confirms_the_order(): void
    {
        config([
            'services.mercadopago.access_token' => 'test-access-token',
            'services.mercadopago.webhook_secret' => 'test-webhook-secret',
        ]);
        Http::preventStrayRequests();
        $user = User::factory()->create();
        $order = $this->createPayableOrder($user);
        $order->payment()->create([
            'status' => 'pending',
            'method' => 'mercado_pago',
        ]);
        Http::fake([
            'https://api.mercadopago.com/v1/payments/987654' => Http::response([
                'id' => 987654,
                'external_reference' => (string) $order->id,
                'transaction_amount' => 50.00,
                'currency_id' => 'BRL',
                'status' => 'approved',
                'status_detail' => 'accredited',
            ]),
        ]);

        $response = $this->postJson(route('mercadopago.webhook'), [
            'type' => 'payment',
            'data' => ['id' => '987654'],
        ], $this->signedHeaders('987654'));

        $response->assertOk();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_PAYMENT_CONFIRMED,
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'external_payment_id' => '987654',
            'status' => 'approved',
        ]);
    }

    public function test_webhook_rejects_an_invalid_signature_without_fetching_payment(): void
    {
        config(['services.mercadopago.webhook_secret' => 'test-webhook-secret']);
        Http::preventStrayRequests();
        $user = User::factory()->create();
        $order = $this->createPayableOrder($user);

        $this->postJson(route('mercadopago.webhook'), [
            'type' => 'payment',
            'data' => ['id' => '987654'],
        ], [
            'x-request-id' => 'request-123',
            'x-signature' => 'ts=1700000000,v1=invalid-signature',
        ])->assertUnauthorized();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_AWAITING_PAYMENT,
        ]);
    }

    private function createPayableOrder(User $user): Order
    {
        $address = Address::create([
            'user_id' => $user->id,
            'cep' => '01000-000',
            'street' => 'Rua Central',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'SP',
            'number' => '10',
            'country' => 'Brasil',
        ]);

        return Order::create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'status' => Order::STATUS_AWAITING_PAYMENT,
            'total' => 50.00,
        ]);
    }

    /** @return array<string, string> */
    private function signedHeaders(string $paymentId): array
    {
        $requestId = 'request-123';
        $timestamp = '1700000000';
        $manifest = 'id:'.strtolower($paymentId).';request-id:'.$requestId.';ts:'.$timestamp.';';

        return [
            'x-request-id' => $requestId,
            'x-signature' => 'ts='.$timestamp.',v1='.hash_hmac('sha256', $manifest, 'test-webhook-secret'),
        ];
    }
}
