<?php

namespace Tests\Feature\Api\State;

use App\Models\User;
use App\Models\State;
use App\Models\Status;
use App\Models\Country;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StateTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetAllCities()
    {
        $response = $this->json('GET', route('api.v1.states.index'));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                ]
            ]
        ]);
    }

    public function testCreateState()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $name = $this->faker->sentence(1, false);
        $country = Country::all()->random();

        $response = $this->json('POST', route('api.v1.states.store'), [
            'name' => $name,
            'country_id' => $country->id,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
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

    public function testGetOneState()
    {
        $state = State::all()->random();

        $response = $this->json('GET', route('api.v1.states.show', [$state]));

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $state->id,
                'name' => $state->name,
            ]
        ]);
    }

    public function testUpdateState()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $state = State::all()->random();
        $name = $this->faker->sentence(1, false);

        $response = $this->json('PUT', route('api.v1.states.update', [$state]), [
            'name' => $name,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ]
        ])->assertJson([
            'data' => [
                'id' => $state->id,
                'name' => $name,
            ]
        ]);
    }

    public function testDeleteState()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $state = State::all()->random();
        $disabledStatus = Status::disabled()->first();

        $response = $this->json('DELETE', route('api.v1.states.destroy', [
            $state,
            'include' => 'status'
        ]));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ]
        ])->assertJson([
            'data' => [
                'id' => $state->id,
                'name' => $state->name,
                'status' => [
                    'id' => $disabledStatus->id,
                    'name' => $disabledStatus->name,
                ],
            ]
        ]);
    }
}
