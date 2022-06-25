<?php

namespace Tests\Feature\Api\Auth;

use Tests\Feature\Api\ApiTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterAuthTest extends ApiTestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
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
