<?php

namespace Tests\Feature\Api\User;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexUserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetAllUsers()
    {
        $loginUser = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($loginUser, ['*']);

        $response = $this->json('GET', route('api.v1.users.index'));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'email',
                    'username',
                ]
            ]
        ]);
    }
}
