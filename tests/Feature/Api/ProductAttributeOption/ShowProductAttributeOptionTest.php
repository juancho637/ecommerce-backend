<?php

namespace Tests\Feature\Api\ProductAttributeOption;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Response;
use App\Models\ProductAttributeOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowProductAttributeOptionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetOneProductAttributeOption()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productAttributeOption = ProductAttributeOption::all()->random();

        $response = $this->json('GET', route('api.v1.product_attribute_options.show', [$productAttributeOption]));

        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'data' => [
                'id' => $productAttributeOption->id,
                'name' => $productAttributeOption->name,
            ]
        ]);
    }
}
