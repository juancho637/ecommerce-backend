<?php

namespace Tests\Feature\Api\City;

use Tests\TestCase;
use App\Models\City;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCityTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testUpdateCity()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $city = City::all()->random();
        $name = $this->faker->sentence(1, false);

        $response = $this->json('PUT', route('api.v1.cities.update', [$city]), [
            'name' => $name,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ]
        ])->assertJson([
            'data' => [
                'id' => $city->id,
                'name' => $name,
            ]
        ]);
    }
}
