<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use Illuminate\Http\Response;
use Tests\Feature\Api\ApiTestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordResetTest extends ApiTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->seed();
    }

    public function testHappyPassPasswordReset()
    {
        $user = User::factory()->roleUser()->create();
        $resetPasswordToken = Str::random(6);
        PasswordReset::create([
            'email' => $user->email,
            'token' => $resetPasswordToken
        ]);

        $newPassword = 'NewPassword';

        $response = $this->json('POST', route('api.v1.auth.password.reset'), [
            'email' => $user->email,
            'token' => $resetPasswordToken,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'access_token',
            'token_type',
        ]);
    }
}
