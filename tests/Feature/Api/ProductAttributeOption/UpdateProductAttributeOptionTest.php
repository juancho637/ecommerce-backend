<?php

namespace Tests\Feature\Api\ProductAttributeOption;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProductAttributeOptionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
}
