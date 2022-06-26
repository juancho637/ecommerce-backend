<?php

namespace Tests\Feature\Api\ProductAttribute;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProductAttributeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
}
