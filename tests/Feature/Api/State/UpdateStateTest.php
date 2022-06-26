<?php

namespace Tests\Feature\Api\State;

use Tests\TestCase;
use App\Models\User;
use App\Models\State;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateStateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
}
