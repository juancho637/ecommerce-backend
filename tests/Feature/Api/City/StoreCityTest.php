<?php

namespace Tests\Feature\Api\City;

use Tests\TestCase;
use App\Models\User;
use App\Models\State;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreCityTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
}
