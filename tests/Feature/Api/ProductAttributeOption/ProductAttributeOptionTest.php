<?php

namespace Tests\Feature\Api\ProductAttributeOption;

use App\Models\User;
use App\Models\Status;
use Laravel\Sanctum\Sanctum;
use App\Models\ProductAttribute;
use Tests\Feature\Api\ApiTestCase;
use App\Models\ProductAttributeOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductAttributeOptionTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetAllProductAttributeOptions()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('GET', route('api.v1.product_attribute_options.index'));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'option',
                ]
            ]
        ]);
    }


    public function testCreateProductAttributeOption()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productAttribute = ProductAttribute::all()->random();
        $name = ($productAttribute->type === ProductAttribute::COLOR_TYPE)
            ? $this->faker->colorName()
            : $this->faker->unique()->sentence(1, false);
        $option = ($productAttribute->type === ProductAttribute::COLOR_TYPE)
            ? $this->faker->hexColor()
            : null;

        $response = $this->json('POST', route('api.v1.product_attribute_options.store'), [
            'name' => $name,
            'product_attribute_id' => $productAttribute->id,
            'option' => $option,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'option',
            ]
        ])->assertJson([
            'data' => [
                'name' => $name,
                'option' => $option,
            ]
        ]);
    }

    public function testGetOneProductAttributeOption()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productAttributeOption = ProductAttributeOption::all()->random();

        $response = $this->json('GET', route('api.v1.product_attribute_options.show', [$productAttributeOption]));

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $productAttributeOption->id,
                'name' => $productAttributeOption->name,
                'option' => $productAttributeOption->option,
            ]
        ]);
    }

    public function testUpdateProductAttributeOption()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productAttributeOption = ProductAttributeOption::all()->random();
        $productAttribute = $productAttributeOption->productAttribute;
        $name = ($productAttribute->type === ProductAttribute::COLOR_TYPE)
            ? $this->faker->colorName()
            : $this->faker->unique()->sentence(1, false);
        $option = ($productAttribute->type === ProductAttribute::COLOR_TYPE)
            ? $this->faker->hexColor()
            : null;

        $response = $this->json('PUT', route('api.v1.product_attribute_options.update', [
            $productAttributeOption
        ]), [
            'name' => $name,
            'option' => $option,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'option',
            ]
        ])->assertJson([
            'data' => [
                'id' => $productAttributeOption->id,
                'name' => $name,
                'option' => $option,
            ]
        ]);
    }

    public function testDeleteProductAttributeOption()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productAttributeOption = ProductAttributeOption::all()->random();
        $disabledStatus = Status::disabled()->first();

        $response = $this->json('DELETE', route('api.v1.product_attribute_options.destroy', [
            $productAttributeOption,
            'include' => 'status'
        ]));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'option',
            ]
        ])->assertJson([
            'data' => [
                'id' => $productAttributeOption->id,
                'name' => $productAttributeOption->name,
                'option' => $productAttributeOption->option,
                'status' => [
                    'id' => $disabledStatus->id,
                    'name' => $disabledStatus->name,
                ],
            ]
        ]);
    }
}
