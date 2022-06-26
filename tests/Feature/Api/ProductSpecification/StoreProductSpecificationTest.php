<?php

namespace Tests\Feature\Api\ProductSpecification;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreProductSpecificationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testCreateProductSpecification()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $product = Product::all()->random();
        $name = $this->faker->sentence(1, false);
        $value = $this->faker->sentence(1, false);

        $response = $this->json('POST', route('api.v1.product_specifications.store'), [
            'product_id' => $product->id,
            'name' => $name,
            'value' => $value,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'value',
            ]
        ])->assertJson([
            'data' => [
                'name' => $name,
                'value' => $value,
            ]
        ]);
    }
}
