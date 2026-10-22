<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_checkout_multiple_selected_items(): void
    {
        $customer = User::factory()->create();
        $cart = Cart::create(['user_id' => $customer->id]);
        $category = Category::create(['name' => 'Miniaturas']);
        $firstProduct = $this->createProduct($category, 'Vaso', 10.25);
        $secondProduct = $this->createProduct($category, 'Suporte', 5.75);
        $unselectedProduct = $this->createProduct($category, 'Chaveiro', 2.00);
        $firstItem = $this->createCartItem($cart, $firstProduct, 2);
        $secondItem = $this->createCartItem($cart, $secondProduct, 3);
        $unselectedItem = $this->createCartItem($cart, $unselectedProduct, 1);

        $response = $this->actingAs($customer)
            ->from(route('cart.index'))
            ->post(route('orders.store'), [
                'cart_item_ids' => [$firstItem->id, $secondItem->id],
            ]);

        $order = Order::query()->firstOrFail();
        $response->assertRedirect(route('orders.show', $order));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $customer->id,
            'total' => '37.75',
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $firstProduct->id,
            'quantity' => 2,
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $secondProduct->id,
            'quantity' => 3,
        ]);
        $this->assertDatabaseCount('order_items', 2);
        $this->assertDatabaseMissing('cart_items', ['id' => $firstItem->id]);
        $this->assertDatabaseMissing('cart_items', ['id' => $secondItem->id]);
        $this->assertDatabaseHas('cart_items', ['id' => $unselectedItem->id]);
    }

    public function test_customer_cannot_checkout_another_users_cart_item(): void
    {
        $customer = User::factory()->create();
        $otherCustomer = User::factory()->create();
        $category = Category::create(['name' => 'Miniaturas']);
        $customerCart = Cart::create(['user_id' => $customer->id]);
        $otherCart = Cart::create(['user_id' => $otherCustomer->id]);
        $customerItem = $this->createCartItem($customerCart, $this->createProduct($category, 'Vaso', 10), 1);
        $otherItem = $this->createCartItem($otherCart, $this->createProduct($category, 'Suporte', 5), 1);

        $this->actingAs($customer)
            ->from(route('cart.index'))
            ->post(route('orders.store'), [
                'cart_item_ids' => [$customerItem->id, $otherItem->id],
            ])
            ->assertRedirectBackWithErrors(['cart_item_ids']);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('cart_items', ['id' => $customerItem->id]);
        $this->assertDatabaseHas('cart_items', ['id' => $otherItem->id]);
    }

    public function test_cart_renders_independent_multi_select_checkboxes(): void
    {
        $customer = User::factory()->create();
        $cart = Cart::create(['user_id' => $customer->id]);
        $category = Category::create(['name' => 'Miniaturas']);
        $this->createCartItem($cart, $this->createProduct($category, 'Vaso', 10), 1);
        $this->createCartItem($cart, $this->createProduct($category, 'Suporte', 5), 1);

        $this->actingAs($customer)
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee('name="cart_item_ids[]"', false)
            ->assertSee('type="checkbox"', false)
            ->assertDontSee('type="radio"', false);
    }

    private function createProduct(Category $category, string $name, float $price): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'description' => 'Produto para teste.',
            'price' => $price,
            'stock' => 10,
            'color' => 'Azul',
            'size' => 'Pequeno',
            'material' => 'PLA',
        ]);
    }

    private function createCartItem(Cart $cart, Product $product, int $quantity): CartItem
    {
        return CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }
}
