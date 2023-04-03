<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\Feature\Api\ApiTestCase;
use App\Notifications\User\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordTest extends ApiTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->seed();
    }

    public function testHappyPassForgotPassword()
    {
        $user = User::factory()->roleUser()->create();

        $response = $this->json('POST', route('api.v1.auth.forgot.password'), [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class);
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'message',
            'code',
        ])->assertJson([
            'message' => __('A code has been sent to your Email.'),
            'code' => Response::HTTP_OK,
        ]);
    }

    public function testForgotPasswordWithBadEmail()
    {
        $email = 'bademail@email.com';

        $response = $this->json('POST', route('api.v1.auth.forgot.password'), [
            'email' => $email,
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJsonStructure([
            'error',
            'code',
        ])->assertJson([
            'error' => __('Incorrect email address'),
            'code' => Response::HTTP_NOT_FOUND,
        ]);
    }
}
