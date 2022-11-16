<?php

namespace Tests\Feature\Api\ProductStock;

use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreProductStockTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testCreateProductStockWithoutAttributeOptions()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::factory()->create();
        $stock = 10;
        $minStock = 5;
        $price = 100000.12;
        $tax = 10.12;

        $response = $this->json('POST', route('api.v1.product_stocks.store'), [
            'product_id' => $product->id,
            'stock' => $stock,
            'min_stock' => $minStock,
            'price' => $price,
            'tax' => $tax,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'stock',
                'min_stock',
                'price',
                'tax',
                'sku',
            ]
        ])->assertJson([
            'data' => [
                'stock' => $stock,
                'min_stock' => $minStock,
                'price' => $price,
                'tax' => $tax,
            ]
        ]);
    }

    public function testCreateProductStockWithOneProductAttribute()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::factory()->create();
        $product->productAttributeOptions()->sync([1, 2]);
        $combination1 = $product->productStocks()->create([
            'status_id' => 1,
            'stock' => 10,
            'min_stock' => 5,
            'price' => 100000.12,
            'tax' => 10.12,
            'sku' => 'prod1',
        ]);
        $combination1->productAttributeOptions()->sync([2]);

        $stock = 10;
        $minStock = 5;
        $price = 100000.12;
        $tax = 10.12;

        $response = $this->json('POST', route('api.v1.product_stocks.store'), [
            'product_id' => $product->id,
            'stock' => $stock,
            'min_stock' => $minStock,
            'price' => $price,
            'tax' => $tax,
            'product_attribute_options' => [1],
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'stock',
                'min_stock',
                'price',
                'tax',
            ]
        ])->assertJson([
            'data' => [
                'stock' => $stock,
                'min_stock' => $minStock,
                'price' => $price,
                'tax' => $tax,
            ]
        ]);
    }

    public function testCreateProductStockWithTwoProductAttributes()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::factory()->create();
        $product->productAttributeOptions()->sync([1, 2, 3, 4]);
        $combination1 = $product->productStocks()->create([
            'status_id' => 1,
            'stock' => 10,
            'min_stock' => 5,
            'price' => 100000.12,
            'tax' => 10.12,
            'sku' => 'prod1',
        ]);
        $combination1->productAttributeOptions()->sync([1, 3]);

        $combination2 = $product->productStocks()->create([
            'status_id' => 1,
            'stock' => 10,
            'min_stock' => 5,
            'price' => 100000.12,
            'tax' => 10.12,
            'sku' => 'prod2',
        ]);
        $combination2->productAttributeOptions()->sync([1, 4]);

        $combination3 = $product->productStocks()->create([
            'status_id' => 1,
            'stock' => 10,
            'min_stock' => 5,
            'price' => 100000.12,
            'tax' => 10.12,
            'sku' => 'prod3',
        ]);
        $combination3->productAttributeOptions()->sync([2, 3]);

        $stock = 10;
        $minStock = 5;
        $price = 100000.12;
        $tax = 10.12;

        $response = $this->json('POST', route('api.v1.product_stocks.store'), [
            'product_id' => $product->id,
            'stock' => $stock,
            'min_stock' => $minStock,
            'price' => $price,
            'tax' => $tax,
            'product_attribute_options' => [2, 4],
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'stock',
                'min_stock',
                'price',
                'tax',
            ]
        ])->assertJson([
            'data' => [
                'stock' => $stock,
                'min_stock' => $minStock,
                'price' => $price,
                'tax' => $tax,
            ]
        ]);
    }
}
