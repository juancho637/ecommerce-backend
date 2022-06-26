<?php

namespace Tests\Feature\Api\State;

use Tests\TestCase;
use App\Models\State;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowStateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
}
