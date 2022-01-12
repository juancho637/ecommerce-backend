<?php

namespace Tests\Feature\Api\City;

use App\Models\City;
use App\Models\User;
use App\Models\State;
use App\Models\Status;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CityTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetAllCities()
    {
        $response = $this->json('GET', route('api.v1.cities.index'));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                ]
            ]
        ]);
    }

    public function testCreateCity()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $name = $this->faker->sentence(1, false);
        $state = State::all()->random();

        $response = $this->json('POST', route('api.v1.cities.store'), [
            'name' => $name,
            'state_id' => $state->id,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ]
        ])->assertJson([
            'data' => [
                'name' => $name,
            ]
        ]);
    }

    public function testGetOneCity()
    {
        $city = City::all()->random();

        $response = $this->json('GET', route('api.v1.cities.show', [$city]));

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $city->id,
                'name' => $city->name,
            ]
        ]);
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

    public function testDeleteCity()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $city = City::all()->random();
        $disabledStatus = Status::disabled()->first();

        $response = $this->json('DELETE', route('api.v1.cities.destroy', [
            $city,
            'include' => 'status'
        ]));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ]
        ])->assertJson([
            'data' => [
                'id' => $city->id,
                'name' => $city->name,
                'status' => [
                    'id' => $disabledStatus->id,
                    'name' => $disabledStatus->name,
                ],
            ]
        ]);
    }
}
