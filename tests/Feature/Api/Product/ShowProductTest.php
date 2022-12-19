<?php

namespace Tests\Feature\Api\Product;

use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Actions\Product\UpsertProductImages;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\ProductStock\UpsertProductStockImages;

class ShowProductTest extends TestCase
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

    public function testGetOneProductWithImages()
    {
        $product = Product::all()->random();
        app(UpsertProductImages::class)($product, [
            [
                'file' => UploadedFile::fake()->image('image.jpg'),
                'location' => 1
            ]
        ]);

        $response = $this->json('GET', route('api.v1.products.show', [
            $product,
            'include' => 'images'
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                    'short_description',
                    'description',
                    'images' => [
                        [
                            'id',
                            'owner_id',
                            'type_resource',
                            'urls',
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
                    'images' => [
                        [
                            'type_resource' => Product::PRODUCT_IMAGE,
                        ]
                    ]
                ]
            ]);
    }

    public function testGetOneProductWithStockImages()
    {
        $product = Product::whereHas('productStocks')
            ->with('productStocks')
            ->get()
            ->random(1)
            ->first();
        app(UpsertProductImages::class)($product, [
            [
                'file' => UploadedFile::fake()->image('image.jpg'),
                'location' => 1
            ]
        ]);

        $product->productStocks()->each(function ($productStock) {
            app(UpsertProductStockImages::class)($productStock, [
                UploadedFile::fake()->image('image.jpg'),
            ]);
        });

        $response = $this->json('GET', route('api.v1.products.show', [
            $product,
            'include' => 'images,product_stocks,stock_images'
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                    'short_description',
                    'description',
                    'stock_images' => [
                        [
                            'id',
                            'owner_id',
                            'type_resource',
                            'urls',
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
                    'stock_images' => [
                        [
                            'type_resource' => ProductStock::PRODUCT_STOCK_IMAGE,
                        ]
                    ]
                ]
            ]);
    }
}
