<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_store_contact_information(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $setting = Setting::create([
            'name' => 'Loja Antiga',
            'cnpj' => '12345678000190',
            'email' => 'old@example.test',
            'telephone' => '11911112222',
        ]);

        $response = $this->actingAs($admin)->put(route('setting.update', $setting), [
            'name' => 'Loja Atualizada',
            'cnpj' => '12345678000190',
            'email' => 'contato@example.test',
            'telephone' => '11999998888',
        ]);

        $response->assertRedirect(route('setting.create'));
        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'name' => 'Loja Atualizada',
            'email' => 'contato@example.test',
            'telephone' => '11999998888',
        ]);
    }
}
