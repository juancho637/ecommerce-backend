<?php

namespace Tests\Feature\Api\ProductAttribute;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowProductAttributeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
}
