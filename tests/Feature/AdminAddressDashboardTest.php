<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAddressDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_all_addresses_and_owner_details(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $firstCustomer = User::factory()->create(['name' => 'Customer One']);
        $secondCustomer = User::factory()->create(['name' => 'Customer Two']);
        $this->createAddress($firstCustomer, 'First customer street');
        $this->createAddress($secondCustomer, 'Second customer street');

        $this->actingAs($admin)
            ->get(route('addresses.index'))
            ->assertOk()
            ->assertSee('Endereços cadastrados')
            ->assertSee('First customer street')
            ->assertSee('Second customer street')
            ->assertSee('Customer One')
            ->assertSee('Customer Two')
            ->assertDontSee('Cadastrar Endereço');
    }

    public function test_customer_only_sees_their_own_addresses(): void
    {
        $customer = User::factory()->create();
        $otherCustomer = User::factory()->create();
        $this->createAddress($customer, 'My customer street');
        $this->createAddress($otherCustomer, 'Other customer street');

        $this->actingAs($customer)
            ->get(route('addresses.index'))
            ->assertOk()
            ->assertSee('My customer street')
            ->assertDontSee('Other customer street');
    }

    public function test_admin_catalog_links_to_three_dashboards_without_cart(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $category = Category::create(['name' => 'Miniaturas']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Vaso de teste',
            'description' => 'Produto para teste.',
            'price' => 10,
            'stock' => 2,
            'color' => 'Azul',
            'size' => 'Pequeno',
            'material' => 'PLA',
        ]);

        $this->actingAs($admin)
            ->get(route('products.index'))
            ->assertOk()
            ->assertSee(route('addresses.index'), false)
            ->assertSee(route('orders.index'), false)
            ->assertSee(route('users.index'), false)
            ->assertDontSee(route('cart.index'), false)
            ->assertDontSee('Adicionar ao carrinho');

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertDontSee('Adicionar ao carrinho');
    }

    private function createAddress(User $user, string $street): Address
    {
        return Address::create([
            'user_id' => $user->id,
            'cep' => '01000-000',
            'street' => $street,
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'SP',
            'number' => '10',
            'country' => 'Brasil',
        ]);
    }
}
