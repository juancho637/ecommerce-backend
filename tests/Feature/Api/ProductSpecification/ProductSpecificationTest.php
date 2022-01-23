<?php

namespace Tests\Feature\Api\ProductSpecification;

use App\Models\User;
use App\Models\Status;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\ApiTestCase;
use App\Models\ProductSpecification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductSpecificationTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetAllProductSpecifications()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('GET', route('api.v1.product_specifications.index'));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'value',
                ]
            ]
        ]);
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

    public function testGetOneProductSpecification()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productSpecification = ProductSpecification::all()->random();

        $response = $this->json('GET', route('api.v1.product_specifications.show', [$productSpecification]));

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $productSpecification->id,
                'name' => $productSpecification->name,
            ]
        ]);
    }

    public function testUpdateProductSpecification()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productSpecification = ProductSpecification::all()->random();
        $name = $this->faker->sentence(1, false);
        $value = $this->faker->sentence(1, false);

        $response = $this->json('PUT', route('api.v1.product_specifications.update', [$productSpecification]), [
            'name' => $name,
            'value' => $value,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'value',
            ]
        ])->assertJson([
            'data' => [
                'id' => $productSpecification->id,
                'name' => $name,
                'value' => $value,
            ]
        ]);
    }

    public function testDeleteProductSpecification()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productSpecification = ProductSpecification::all()->random();
        $disabledStatus = Status::disabled()->first();

        $response = $this->json('DELETE', route('api.v1.product_specifications.destroy', [
            $productSpecification,
            'include' => 'status'
        ]));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'value',
            ]
        ])->assertJson([
            'data' => [
                'id' => $productSpecification->id,
                'name' => $productSpecification->name,
                'value' => $productSpecification->value,
                'status' => [
                    'id' => $disabledStatus->id,
                    'name' => $disabledStatus->name,
                ],
            ]
        ]);
    }
}
