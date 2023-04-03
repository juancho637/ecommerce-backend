<?php

namespace Tests\Feature\Api\User;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testUpdateAdminPassword()
    {
        $loginUser = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($loginUser, ['*']);

        $newPassword = 'new-password';

        $response = $this->json('PUT', route('api.v1.users.password.update', [$loginUser]), [
            'password' => 'password',
            'new_password' => $newPassword,
            'new_password_confirmation' => $newPassword,
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'username',
            ]
        ])->assertJson([
            'data' => [
                'id' => $loginUser->id,
                'name' => $loginUser->name,
                'email' => $loginUser->email,
                'username' => $loginUser->username,
            ]
        ]);
    }

    public function testUpdateUserPassword()
    {
        $loginUser = User::factory()->roleUser()->create();
        Sanctum::actingAs($loginUser, ['*']);

        $newPassword = 'new-password';

        $response = $this->json('PUT', route('api.v1.users.password.update', [$loginUser]), [
            'password' => 'password',
            'new_password' => $newPassword,
            'new_password_confirmation' => $newPassword,
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'username',
            ]
        ])->assertJson([
            'data' => [
                'id' => $loginUser->id,
                'name' => $loginUser->name,
                'email' => $loginUser->email,
                'username' => $loginUser->username,
            ]
        ]);
    }
}
