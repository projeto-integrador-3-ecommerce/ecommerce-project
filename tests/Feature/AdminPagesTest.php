<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        return $admin;
    }

    public function test_admin_sees_register_product_button_on_catalog(): void
    {
        $this->actingAs($this->admin())
            ->get(route('products.index'))
            ->assertOk()
            ->assertSee('Cadastrar produto')
            ->assertSee(route('products.create'));
    }

    public function test_customers_do_not_see_register_product_button_on_catalog(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->get(route('products.index'))
            ->assertOk()
            ->assertDontSee('Cadastrar produto');
    }

    public function test_product_edit_page_is_rendered_for_admins(): void
    {
        $category = Category::create(['name' => 'Miniaturas']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Vaso articulado',
            'description' => 'Uma peça de teste.',
            'price' => 25.50,
            'stock' => 4,
            'color' => 'Azul',
            'size' => 'Pequeno',
            'material' => 'PLA',
        ]);

        $this->actingAs($this->admin())
            ->get(route('products.edit', $product))
            ->assertOk()
            ->assertSee('Editar produto')
            ->assertSee('Vaso articulado')
            ->assertSee('Salvar alterações');
    }

    public function test_category_create_and_edit_pages_are_rendered_for_admins(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('categories.create'))
            ->assertOk()
            ->assertSee('Criar categoria');

        $category = Category::create(['name' => 'Miniaturas']);

        $this->actingAs($admin)
            ->get(route('categories.edit', $category))
            ->assertOk()
            ->assertSee('Editar categoria')
            ->assertSee('Miniaturas');
    }

    public function test_categories_index_shows_admin_actions_only_for_admins(): void
    {
        $category = Category::create(['name' => 'Miniaturas']);

        $this->actingAs($this->admin())
            ->get(route('categories.index'))
            ->assertOk()
            ->assertSee(route('categories.edit', $category));

        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->get(route('categories.index'))
            ->assertOk()
            ->assertDontSee(route('categories.edit', $category));
    }

    public function test_admin_dashboard_links_to_register_product_and_store_settings(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee(route('products.create'))
            ->assertSee(route('setting.create'));
    }

    public function test_store_settings_page_is_rendered_for_admins(): void
    {
        $this->actingAs($this->admin())
            ->get(route('setting.create'))
            ->assertOk()
            ->assertSee('Dados da loja');
    }
}
