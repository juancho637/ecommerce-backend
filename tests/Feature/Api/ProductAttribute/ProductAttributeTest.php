<?php

namespace Tests\Feature\Api\ProductAttribute;

use App\Models\User;
use App\Models\Status;
use Laravel\Sanctum\Sanctum;
use App\Models\ProductAttribute;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductAttributeTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetAllProductAttributes()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('GET', route('api.v1.product_attributes.index'));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'type',
                ]
            ]
        ]);
    }


    public function testCreateProductAttribute()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $name = $this->faker->unique()->sentence(1, false);
        $type = $this->faker->randomElement(ProductAttribute::TYPES);

        $response = $this->json('POST', route('api.v1.product_attributes.store'), [
            'name' => $name,
            'type' => $type,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
            ]
        ])->assertJson([
            'data' => [
                'name' => $name,
                'type' => $type,
            ]
        ]);
    }

    public function testGetOneProductAttribute()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productAttribute = ProductAttribute::all()->random();

        $response = $this->json('GET', route('api.v1.product_attributes.show', [$productAttribute]));

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $productAttribute->id,
                'name' => $productAttribute->name,
            ]
        ]);
    }

    public function testUpdateProductAttribute()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productAttribute = ProductAttribute::all()->random();
        $name = $this->faker->sentence(1, false);
        $type = $this->faker->randomElement(ProductAttribute::TYPES);

        $response = $this->json('PUT', route('api.v1.product_attributes.update', [$productAttribute]), [
            'name' => $name,
            'type' => $type,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
            ]
        ])->assertJson([
            'data' => [
                'id' => $productAttribute->id,
                'name' => $name,
                'type' => $type,
            ]
        ]);
    }

    public function testDeleteProductAttribute()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productAttribute = ProductAttribute::all()->random();
        $disabledStatus = Status::disabled()->first();

        $response = $this->json('DELETE', route('api.v1.product_attributes.destroy', [
            $productAttribute,
            'include' => 'status'
        ]));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'type',
            ]
        ])->assertJson([
            'data' => [
                'id' => $productAttribute->id,
                'name' => $productAttribute->name,
                'type' => $productAttribute->type,
                'status' => [
                    'id' => $disabledStatus->id,
                    'name' => $disabledStatus->name,
                ],
            ]
        ]);
    }
}
