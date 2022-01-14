<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testAuthLogin()
    {
        $user = User::factory()->roleUser()->create();

        $response = $this->json('POST', route('api.v1.auth.login'), [
            'username' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'access_token',
            'token_type',
        ]);
    }

    public function testAuthRegister()
    {
        $name = $this->faker->sentence(1, false);
        $email = $this->faker->unique()->safeEmail();
        $username = explode('@', $email)[0];
        $password = 'password';

        $response = $this->json('POST', route('api.v1.auth.register'), [
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'access_token',
            'token_type',
        ]);
    }
}
