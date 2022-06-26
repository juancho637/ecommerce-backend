<?php

namespace Tests\Feature\Api\ProductSpecification;

use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use Laravel\Sanctum\Sanctum;
use App\Models\ProductSpecification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyProductSpecificationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testDeleteProductSpecification()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $productSpecification = ProductSpecification::all()->random();
        $disabledStatus = Status::disabled()->first();

        $response = $this->json('DELETE', route('api.v1.product_specifications.destroy', [
            $productSpecification,
            'include' => 'status'
        ]));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'value',
            ]
        ])->assertJson([
            'data' => [
                'id' => $productSpecification->id,
                'name' => $productSpecification->name,
                'value' => $productSpecification->value,
                'status' => [
                    'id' => $disabledStatus->id,
                    'name' => $disabledStatus->name,
                ],
            ]
        ]);
    }
}
