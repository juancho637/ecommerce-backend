<?php

namespace Tests\Feature\Api\State;

use Tests\TestCase;
use App\Models\User;
use App\Models\Country;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreStateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
}
