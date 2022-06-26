<?php

namespace Tests\Feature\Api\ProductSpecification;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\ProductSpecification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProductSpecificationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
}
