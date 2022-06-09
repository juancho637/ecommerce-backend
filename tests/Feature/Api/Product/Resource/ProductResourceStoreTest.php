<?php

namespace Tests\Feature\Api\Product\Resource;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductResourceStoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
    }

    public function testCreateProductResource()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::all()->random();

        $response = $this->json('POST', route('api.v1.products.photos.store', [
            $product
        ]), [
            'photo' => UploadedFile::fake()->image('image.jpg'),
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'url',
                    'type_resource',
                ]
            ]);
    }

    public function testCreateProductResourceWithError()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::all()->random();
        $product->savePhotos([
            UploadedFile::fake()->image('image.jpg'),
            UploadedFile::fake()->image('image.jpg'),
            UploadedFile::fake()->image('image.jpg'),
            UploadedFile::fake()->image('image.jpg'),
        ]);

        $response = $this->json('POST', route('api.v1.products.photos.store', [
            $product
        ]), [
            'photo' => UploadedFile::fake()->image('image.jpg'),
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'error',
                'code',
            ])
            ->assertJson([
                'error' => "The product exceeds the maximum of photos",
                'code' => 400,
            ]);
    }
}
