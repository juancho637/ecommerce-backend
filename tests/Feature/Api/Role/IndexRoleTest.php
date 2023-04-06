<?php

namespace Tests\Feature\Api\Role;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexRoleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testGetAllRoles()
    {
        $user = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('GET', route('api.v1.roles.index'));

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                ]
            ]
        ]);
    }
}
