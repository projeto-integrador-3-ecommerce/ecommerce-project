<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_address_index(): void
    {
        $this->get('/addresses')
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_address_index_renders_without_saved_addresses(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/addresses')
            ->assertOk()
            ->assertSee('Meus endereços')
            ->assertDontSee('Usar este endereço');
    }
}
