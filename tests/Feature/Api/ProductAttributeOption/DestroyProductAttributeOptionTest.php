<?php

namespace Tests\Feature\Api\ProductAttributeOption;

use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use Laravel\Sanctum\Sanctum;
use App\Models\ProductAttributeOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyProductAttributeOptionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testDeleteProductAttributeOption()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productAttributeOption = ProductAttributeOption::all()->random();
        $disabledStatus = Status::disabled()->first();

        $response = $this->json('DELETE', route('api.v1.product_attribute_options.destroy', [
            $productAttributeOption,
            'include' => 'status'
        ]));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'option',
            ]
        ])->assertJson([
            'data' => [
                'id' => $productAttributeOption->id,
                'name' => $productAttributeOption->name,
                'option' => $productAttributeOption->option,
                'status' => [
                    'id' => $disabledStatus->id,
                    'name' => $disabledStatus->name,
                ],
            ]
        ]);
    }
}
