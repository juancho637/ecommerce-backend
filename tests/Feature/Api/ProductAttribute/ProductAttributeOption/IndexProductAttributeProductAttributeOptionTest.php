<?php

namespace Tests\Feature\Api\ProductAttribute\ProductAttributeOption;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Response;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexProductAttributeProductAttributeOptionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetAllProductAttributeOptionsByProductAttribute()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productAttribute = ProductAttribute::all()->random();

        $response = $this->json('GET', route(
            'api.v1.product_attributes.product_attribute_options.index',
            [
                $productAttribute,
                'include' => 'status,product_attribute',
            ]
        ));

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                ]
            ]
        ]);
    }

    public function testGetEmptyProductAttributeOptionsByProductAttribute()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productAttribute = ProductAttribute::factory()->create();

        $response = $this->json('GET', route(
            'api.v1.product_attributes.product_attribute_options.index',
            [
                $productAttribute,
                'include' => 'status,product_attribute',
            ]
        ));

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([]);
    }
}
