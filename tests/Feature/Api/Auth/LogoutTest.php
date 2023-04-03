<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Response;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends ApiTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testAuthLogout()
    {
        $user = User::factory()->roleUser()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->json('POST', route('api.v1.auth.logout'));

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'message',
            'code',
        ])->assertJson([
            'message' => __('Logged out'),
            'code' => Response::HTTP_OK,
        ]);
    }
}
