<?php

namespace Tests\Feature\Api\Product\ProductStock;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexProductTypeTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetAllProductTypes()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('GET', route('api.v1.product_types.index'));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                [
                    'name',
                ]
            ]
        ]);
    }
}
