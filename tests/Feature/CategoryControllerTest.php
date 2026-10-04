<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_description_is_saved_and_rendered(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        $response = $this->actingAs($admin)->post(route('categories.store'), [
            'name' => 'Miniaturas',
            'description' => 'Peças em escala para colecionadores.',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Miniaturas',
            'description' => 'Peças em escala para colecionadores.',
        ]);

        $this->get(route('categories.index'))
            ->assertSee('Peças em escala para colecionadores.');
    }
}
