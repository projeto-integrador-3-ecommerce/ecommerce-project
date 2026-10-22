<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_order_list(): void
    {
        $this->get(route('orders.index'))
            ->assertRedirect(route('login'));
    }

    public function test_customer_only_sees_their_own_orders(): void
    {
        $customer = User::factory()->create(['name' => 'Customer One']);
        $otherCustomer = User::factory()->create(['name' => 'Customer Two']);
        $customerOrder = $this->createOrder($customer);
        $otherOrder = $this->createOrder($otherCustomer);

        $this->actingAs($customer)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee('Pedido #'.$customerOrder->id)
            ->assertDontSee('Pedido #'.$otherOrder->id);

        $this->get(route('orders.show', $otherOrder))
            ->assertNotFound();
    }

    public function test_customer_cannot_access_admin_routes(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->get(route('products.create'))
            ->assertForbidden();
    }

    public function test_existing_customer_can_be_promoted_to_admin_from_cli(): void
    {
        $user = User::factory()->create();

        $this->artisan('users:promote-admin', ['email' => $user->email])
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_admin' => true,
        ]);
    }

    public function test_admin_can_view_orders_from_all_customers(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $customer = User::factory()->create(['name' => 'Visible Customer']);
        $this->createOrder($customer);

        $this->actingAs($admin)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee('Visible Customer');
    }

    public function test_customer_cannot_edit_another_users_address(): void
    {
        $customer = User::factory()->create();
        $otherCustomer = User::factory()->create();
        $address = Address::create([
            'user_id' => $otherCustomer->id,
            'cep' => '01000-000',
            'street' => 'Rua Central',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'SP',
            'number' => '10',
            'country' => 'Brasil',
        ]);

        $this->actingAs($customer)
            ->get(route('addresses.edit', $address))
            ->assertNotFound();
    }

    public function test_customer_cannot_change_another_users_cart_item(): void
    {
        $customer = User::factory()->create();
        $otherCustomer = User::factory()->create();
        $category = Category::create(['name' => 'Test category']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test product',
            'description' => 'Test description',
            'price' => 25,
            'stock' => 10,
            'color' => 'Blue',
            'size' => 'Small',
            'material' => 'PLA',
        ]);
        $cart = Cart::create(['user_id' => $otherCustomer->id]);
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($customer)
            ->post(route('cart-items.add', $cartItem))
            ->assertNotFound();

        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 1,
        ]);
    }

    public function test_admin_can_update_order_status(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $order = $this->createOrder(User::factory()->create());

        $this->actingAs($admin)
            ->patch(route('orders.status', $order), [
                'status' => Order::STATUS_SHIPPED,
            ])
            ->assertRedirect(route('orders.show', $order));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_SHIPPED,
        ]);
    }

    public function test_order_history_formats_created_at_for_brazilian_reading(): void
    {
        $customer = User::factory()->create();
        $order = $this->createOrder($customer);
        Order::query()->whereKey($order->id)->update([
            'created_at' => '2026-01-02 15:04:00',
            'updated_at' => '2026-01-02 15:04:00',
        ]);

        $this->actingAs($customer)
            ->get(route('orders.index'))
            ->assertSee('02/01/2026 15:04')
            ->assertSee('Pedido #'.$order->id);
    }

    private function createOrder(User $user): Order
    {
        return Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => 25.00,
        ]);
    }
}
