<?php

namespace Tests\Feature\Api\ProductSpecification;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\ProductSpecification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowProductSpecificationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
}
