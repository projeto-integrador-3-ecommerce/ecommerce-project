<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_description_and_supabase_image_are_saved_and_rendered(): void
    {
        $this->app['config']->set('services.supabase.url', 'https://project.supabase.co');
        $this->app['config']->set('services.supabase.bucket', 'products');
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $imagePath = 'supabase:categories/aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa.png';

        $response = $this->actingAs($admin)->post(route('categories.store'), [
            'name' => 'Miniaturas',
            'description' => 'Peças em escala para colecionadores.',
            'image_path' => $imagePath,
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Miniaturas',
            'description' => 'Peças em escala para colecionadores.',
            'image' => $imagePath,
        ]);

        $imageUrl = 'https://project.supabase.co/storage/v1/object/public/products/categories/aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa.png';

        $this->get(route('categories.index'))
            ->assertSee('Peças em escala para colecionadores.')
            ->assertSee($imageUrl);

        $this->get(route('storefront.home'))
            ->assertSee($imageUrl);

        $this->get(route('storefront.search'))
            ->assertSee($imageUrl);
    }
}
