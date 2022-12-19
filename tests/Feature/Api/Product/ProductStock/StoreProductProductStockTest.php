<?php

namespace Tests\Feature\Api\Product\ProductStock;

use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreProductProductStockTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
    }

    public function testCreateStockToProductWithOneAttribute()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::factory()
            ->isVariable()
            ->typeProduct()
            ->create();
        $product->productAttributeOptions()->sync([1, 2]);
        $combination1 = $product->productStocks()->create([
            'status_id' => 1,
            'stock' => 10,
            'price' => 100000.12,
            'sku' => 'prod1',
        ]);
        $combination1->productAttributeOptions()->sync([2]);

        $price = 100000.12;
        $stock = 10;
        $width = 10;
        $height = 10;
        $length = 10;
        $weight = 10;

        $response = $this->json('POST', route(
            'api.v1.products.product_stocks.store',
            [$product, 'include' => 'images']
        ), [
            'price' => $price,
            'product_attribute_options' => [1],
            'images' => [
                UploadedFile::fake()->image('image.jpg'),
            ],
            'stock' => $stock,
            'width' => $width,
            'height' => $height,
            'length' => $length,
            'weight' => $weight,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'price',
                'sku',
                'stock',
                'width',
                'height',
                'length',
                'weight',
                'images',
            ]
        ])->assertJson([
            'data' => [
                'price' => $price,
                'stock' => $stock,
                'width' => $width,
                'height' => $height,
                'length' => $length,
                'weight' => $weight,
            ]
        ]);
    }

    public function testCreateStockToServiceWithOneAttribute()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::factory()
            ->isVariable()
            ->typeService()
            ->create();
        $product->productAttributeOptions()->sync([1, 2]);
        $combination1 = $product->productStocks()->create([
            'status_id' => 1,
            'stock' => 10,
            'price' => 100000.12,
            'sku' => 'prod1',
        ]);
        $combination1->productAttributeOptions()->sync([2]);

        $price = 100000.12;

        $response = $this->json('POST', route(
            'api.v1.products.product_stocks.store',
            [$product]
        ), [
            'price' => $price,
            'product_attribute_options' => [1],
            'images' => [
                UploadedFile::fake()->image('image.jpg'),
            ],
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'price',
            ]
        ])->assertJson([
            'data' => [
                'price' => $price,
            ]
        ]);
    }

    public function testCreateStockToProductWithTwoAttributes()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

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

        $combination2 = $product->productStocks()->create([
            'status_id' => 1,
            'stock' => 10,
            'price' => 100000.12,
            'sku' => 'prod2',
        ]);
        $combination2->productAttributeOptions()->sync([1, 4]);

        $combination3 = $product->productStocks()->create([
            'status_id' => 1,
            'stock' => 10,
            'price' => 100000.12,
            'sku' => 'prod3',
        ]);
        $combination3->productAttributeOptions()->sync([2, 3]);

        $price = 100000.12;
        $stock = 10;
        $width = 10;
        $height = 10;
        $length = 10;
        $weight = 10;

        $response = $this->json('POST', route(
            'api.v1.products.product_stocks.store',
            [$product]
        ), [
            'price' => $price,
            'product_attribute_options' => [2, 4],
            'images' => [
                UploadedFile::fake()->image('image.jpg'),
            ],
            'stock' => $stock,
            'width' => $width,
            'height' => $height,
            'length' => $length,
            'weight' => $weight,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'price',
                'stock',
                'width',
                'height',
                'length',
                'weight',
            ]
        ])->assertJson([
            'data' => [
                'price' => $price,
                'stock' => $stock,
                'width' => $width,
                'height' => $height,
                'length' => $length,
                'weight' => $weight,
            ]
        ]);
    }
}
