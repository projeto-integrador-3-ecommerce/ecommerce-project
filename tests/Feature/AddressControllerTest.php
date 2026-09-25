<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    public function test_address_index_renders_without_an_order(): void
    {
        Schema::dropIfExists('addresses');
        Schema::create('addresses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable();
        });

        $response = $this->get('/addresses');

        $response->assertOk();
        $response->assertSee('Meus endereços');
        $response->assertDontSee('Usar este endereço');
    }
}
