<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SupabaseImageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_uploads_a_category_image_to_supabase_storage(): void
    {
        $this->app['config']->set('services.supabase.url', 'https://project.supabase.co');
        $this->app['config']->set('services.supabase.service_role_key', 'sb_secret_test-key');
        $this->app['config']->set('services.supabase.bucket', 'products');

        Http::preventStrayRequests();
        Http::fake([
            'https://project.supabase.co/storage/v1/object/products/categories/*' => Http::response(['Key' => 'categories/image.png'], 200),
        ]);

        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $response = $this->actingAs($admin)->postJson(route('supabase.images.store'), [
            'folder' => 'categories',
            'image' => UploadedFile::fake()->create('category.png', 100, 'image/png'),
        ]);

        $response->assertOk();
        $this->assertMatchesRegularExpression(
            '/^supabase:categories\/[a-f0-9-]{36}\.png$/i',
            $response->json('path'),
        );

        Http::assertSent(fn (Request $request): bool => $request->method() === 'POST'
            && str_starts_with($request->url(), 'https://project.supabase.co/storage/v1/object/products/categories/')
            && $request->hasHeader('apikey', 'sb_secret_test-key')
            && ! $request->hasHeader('Authorization'));
    }

    public function test_non_admin_cannot_upload_an_image(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson(route('supabase.images.store'), [
            'folder' => 'products',
            'image' => UploadedFile::fake()->create('product.png', 100, 'image/png'),
        ])->assertForbidden();
    }

    public function test_upload_rejects_non_image_files(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        $this->actingAs($admin)->postJson(route('supabase.images.store'), [
            'folder' => 'products',
            'image' => UploadedFile::fake()->create('notes.txt', 10, 'text/plain'),
        ])->assertInvalid('image');
    }

    public function test_upload_reports_missing_supabase_configuration_without_falling_back_to_local_storage(): void
    {
        $this->app['config']->set('services.supabase.url', null);
        $this->app['config']->set('services.supabase.service_role_key', null);
        $this->app['config']->set('services.supabase.bucket', null);

        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        $this->actingAs($admin)->postJson(route('supabase.images.store'), [
            'folder' => 'products',
            'image' => UploadedFile::fake()->create('product.png', 100, 'image/png'),
        ])
            ->assertServiceUnavailable()
            ->assertJsonPath('message', 'Supabase Storage não está configurado. Defina SUPABASE_URL, SUPABASE_SERVICE_ROLE_KEY e SUPABASE_BUCKET no Render.');
    }
}
