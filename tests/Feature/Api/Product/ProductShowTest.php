<?php

namespace Tests\Feature\Api\Product;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Product\UpdateOrCreateProductPhotos;

class ProductShowTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
    }

    public function testGetOneProduct()
    {
        $product = Product::all()->random();

        $response = $this->json('GET', route('api.v1.products.show', [$product]));

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'short_description' => $product->short_description,
                'description' => $product->description,
            ]
        ]);
    }

    public function testGetOneProductWithPhotos()
    {
        $product = Product::all()->random();
        app(UpdateOrCreateProductPhotos::class)($product, [
            [
                'file' => UploadedFile::fake()->image('image.jpg'),
                'location' => 1
            ]
        ]);

        $response = $this->json('GET', route('api.v1.products.show', [
            $product,
            'include' => 'photos'
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                    'short_description',
                    'description',
                    'photos' => [
                        [
                            'id',
                            'url',
                            'type_resource',
                        ]
                    ]
                ]
            ])->assertJson([
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'short_description' => $product->short_description,
                    'description' => $product->description,
                ]
            ]);
    }
}
