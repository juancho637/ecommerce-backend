<?php

namespace Tests\Feature\Api\ProductStock;

use App\Models\User;
use App\Models\Product;
use App\Models\Resource;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProductStockTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testUpdateStockToProductWithOneAttribute()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $image = Resource::factory()->isImage()->create();
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
            'stock' => 10,
            'width' => 10,
            'height' => 10,
            'length' => 10,
            'weight' => 10,
        ]);
        $combination1->productAttributeOptions()->sync([1, 2]);

        $price = 110000;
        $stock = 20;
        $width = 11;

        $response = $this->json('PUT', route(
            'api.v1.product_stocks.update',
            [$combination1, 'include' => 'images']
        ), [
            'price' => $price,
            'stock' => $stock,
            'width' => $width,
            'images' => [
                'attach' => [
                    $image->id,
                ],
            ],
        ]);

        // dd($response->decodeResponseJson());

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'price',
                'sku',
                'stock',
                'width',
                'height',
                'length',
                'weight',
                'images' => [
                    [
                        'id',
                        'urls',
                        'owner_id',
                        'type_resource',
                    ],
                ],
            ]
        ])->assertJson([
            'data' => [
                'price' => $price,
                'stock' => $stock,
                'width' => $width,
            ]
        ]);
    }
}
