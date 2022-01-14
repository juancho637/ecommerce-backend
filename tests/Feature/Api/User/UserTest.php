<?php

namespace Tests\Feature\Api\User;

use App\Models\Role;
use App\Models\User;
use App\Models\Status;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

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

        $response->assertStatus(200)->assertJsonStructure([
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

    public function testGetOneUser()
    {
        $loginUser = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($loginUser, ['*']);

        $user = User::all()->random();

        $response = $this->json('GET', route('api.v1.users.show', [$user]));

        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
            ]
        ]);
    }

    public function testUpdateUser()
    {
        $loginUser = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($loginUser, ['*']);

        $user = User::all()->random();
        $name = $this->faker->sentence(1, false);

        $response = $this->json('PUT', route('api.v1.users.update', [$user]), [
            'name' => $name,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'username',
            ]
        ])->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $name,
                'email' => $user->email,
                'username' => $user->username,
            ]
        ]);
    }

    public function testDeleteUser()
    {
        $loginUser = User::factory()->roleAdmin()->create();
        Sanctum::actingAs($loginUser, ['*']);

        $user = User::all()->random();
        $disabledStatus = Status::disabled()->first();

        $response = $this->json('DELETE', route('api.v1.users.destroy', [
            $user,
            'include' => 'status'
        ]));

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'username',
            ]
        ])->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'status' => [
                    'id' => $disabledStatus->id,
                    'name' => $disabledStatus->name,
                ],
            ]
        ]);
    }
}
