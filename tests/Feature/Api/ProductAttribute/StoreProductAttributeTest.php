<?php

namespace Tests\Feature\Api\ProductAttribute;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreProductAttributeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
}
