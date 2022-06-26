<?php

namespace Tests\Feature\Api\ProductAttributeOption;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreProductAttributeOptionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
}
