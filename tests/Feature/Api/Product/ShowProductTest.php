<?php

namespace Tests\Feature\Api\Product;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Resource;
use App\Models\ProductStock;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    public function testGetOneProductWithRelationships()
    {
        $product = Product::factory()
            ->isVariable()
            ->typeProduct()
            ->create();
        $product->productAttributeOptions()->sync([1, 2, 3, 4]);
        $combination1 = $product->productStocks()->create([
            'status_id' => 1,
            'stock' => 10,
            'price' => 100000.12,
            'sku' => 'prod1',
        ]);
        $combination1->productAttributeOptions()->sync([1, 3]);

        $response = $this->json('GET', route('api.v1.products.show', [
            $product,
            'include' => 'product_stocks,product_attribute_options,stock_images'
        ]));

        dd($response->decodeResponseJson());

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
        Resource::factory()
            ->isImage()
            ->productOwner($product)
            ->withOptions(['location' => 1])
            ->create();

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

        Resource::factory()
            ->isImage()
            ->productOwner($product)
            ->withOptions(['location' => 1])
            ->create();

        $product->productStocks()->each(function ($productStock) {
            Resource::factory()
                ->isImage()
                ->productStockOwner($productStock->id)
                ->create();
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
