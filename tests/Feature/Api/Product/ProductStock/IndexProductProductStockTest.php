<?php

namespace Tests\Feature\Api\Product\ProductStock;

use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexProductProductStockTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetAllStocksOfOneProduct()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::whereHas('productStocks')
            ->with('productStocks')
            ->get()
            ->random(1)
            ->first();

        $response = $this->json('GET', route('api.v1.products.product_stocks.index', [
            $product,
            'include' => 'images'
        ]));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'price',
                    'sku',
                ]
            ]
        ]);
    }
}
