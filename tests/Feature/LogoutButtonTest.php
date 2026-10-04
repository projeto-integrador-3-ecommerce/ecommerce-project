<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutButtonTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_does_not_see_logout_control(): void
    {
        $this->get(route('products.index'))
            ->assertOk()
            ->assertDontSee('Sair')
            ->assertDontSee(route('logout'), false);
    }

    public function test_customer_sees_post_logout_form(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->get(route('products.index'))
            ->assertOk()
            ->assertSee('Sair')
            ->assertSee('action="'.route('logout').'"', false)
            ->assertSee('method="POST"', false)
            ->assertSee('name="_token"', false);
    }

    public function test_admin_sees_post_logout_form(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        $this->actingAs($admin)
            ->get(route('products.index'))
            ->assertOk()
            ->assertSee('Sair')
            ->assertSee('action="'.route('logout').'"', false)
            ->assertSee('method="POST"', false)
            ->assertSee('name="_token"', false);
    }
}
