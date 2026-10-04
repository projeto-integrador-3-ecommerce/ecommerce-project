<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_image_is_uploaded_and_saved(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $category = Category::create(['name' => 'Miniaturas']);

        $response = $this->actingAs($admin)->post(route('products.store'), [
            'category_id' => $category->id,
            'name' => 'Vaso articulado',
            'description' => 'Uma peça de teste.',
            'price' => 25.50,
            'stock' => 4,
            'color' => 'Azul',
            'size' => 'Pequeno',
            'material' => 'PLA',
            'image' => UploadedFile::fake()->create('vaso.png', 100, 'image/png'),
        ]);

        $response->assertRedirect(route('products.index'));
        $product = Product::query()->firstOrFail();

        Storage::disk('public')->assertExists($product->image);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'image' => $product->image,
        ]);
    }

    public function test_product_update_replaces_image_and_uses_product_field_names(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/old.png', 'old image');
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $category = Category::create(['name' => 'Miniaturas']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Produto antigo',
            'description' => 'Descrição antiga.',
            'price' => 10,
            'stock' => 2,
            'color' => 'Verde',
            'size' => 'Médio',
            'material' => 'PLA',
            'image' => 'products/old.png',
        ]);

        $response = $this->actingAs($admin)->put(route('products.update', $product), [
            'category_id' => $category->id,
            'name' => 'Produto atualizado',
            'description' => 'Descrição atualizada.',
            'price' => 35.75,
            'stock' => 5,
            'color' => 'Azul',
            'size' => 'Grande',
            'material' => 'PETG',
            'image' => UploadedFile::fake()->create('novo.png', 100, 'image/png'),
        ]);

        $response->assertRedirect(route('products.show', $product));
        $product->refresh();

        Storage::disk('public')->assertMissing('products/old.png');
        Storage::disk('public')->assertExists($product->image);
        $this->assertSame('Produto atualizado', $product->name);
    }
}
