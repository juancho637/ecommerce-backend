<?php

namespace Tests\Feature\Api\User;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testCreateUser()
    {
        $loginUser = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($loginUser, ['*']);

        $name = $this->faker->sentence(1, false);
        $email = $this->faker->unique()->safeEmail();
        $username = explode('@', $email)[0];
        $password = 'password';
        $role = Role::admin()->value('id');

        $response = $this->json('POST', route('api.v1.users.store'), [
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'password_confirmation' => $password,
            'role' => $role,
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'username',
            ]
        ])->assertJson([
            'data' => [
                'name' => $name,
                'email' => $email,
                'username' => $username,
            ]
        ]);
    }
}
