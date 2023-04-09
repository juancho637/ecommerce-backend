<?php

namespace Tests\Feature\Api\Product;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Response;
use App\Models\ProductAttributeOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinishProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testFinishProductWithAttributes()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::factory()
            ->isVariable()
            ->typeService()
            ->create();
        $option = ProductAttributeOption::whereIn('id', [1])->with('productAttribute')->first();
        $product->productAttributeOptions()->sync([$option->id]);
        $combination1 = $product->productStocks()->create([
            'status_id' => 1,
            'price' => 100000.12,
            'sku' => 'prod1',
        ]);
        $combination1->productAttributeOptions()->sync([1]);
        $specifications = [
            [
                'name' => $option->productAttribute->name,
                'value' => $option->name,
            ],
            [
                'name' => 'spec2',
                'value' => 'value2',
            ],
        ];

        $response = $this->json('POST', route('api.v1.product_specifications.store', [
            $product
        ]), [
            'specifications' => $specifications,
        ]);

        $response->assertStatus(Response::HTTP_CREATED)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'value',
                ]
            ]
        ])->assertJsonFragment([
            'name' => 'spec2',
            'value' => 'value2',
        ]);
    }
}
