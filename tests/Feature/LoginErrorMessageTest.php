<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginErrorMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_credentials_are_shown_on_login_form(): void
    {
        User::factory()->create([
            'email' => 'customer@example.test',
            'password' => 'correct-password',
        ]);

        $response = $this->from(route('login'))
            ->followingRedirects()
            ->post(route('login.store'), [
                'email' => 'customer@example.test',
                'password' => 'wrong-password',
            ]);

        $response->assertOk()
            ->assertSee('As credenciais fornecidas não correspondem aos nossos registros.');
    }

    public function test_registration_form_exposes_password_and_field_limits(): void
    {
        $this->get(route('register'))
            ->assertSee('maxlength="150"', false)
            ->assertSee('maxlength="255"', false)
            ->assertSee('minlength="8"', false);
    }
}
