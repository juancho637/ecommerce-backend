<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\ProviderRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;

class AuthController extends ApiController
{
    public function login(LoginRequest $loginRequest)
    {
        $fieldType = filter_var($loginRequest->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (!Auth::attempt([
            $fieldType => $loginRequest->username,
            'password' => $loginRequest->password
        ])) {
            return $this->errorResponse(__('Invalid login'), 401);
        }

        $user = User::where($fieldType, $loginRequest->username)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->jsonResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function register(RegisterRequest $registerRequest)
    {
        $user = User::create([
            'name' => $registerRequest->name,
            'email' => $registerRequest->email,
            'username' => $registerRequest->username,
            'password' => Hash::make($registerRequest->password),
            'status_id' => Status::enabled()->value('id'),
        ]);
        $user->syncRoles(Role::user()->value('id'));

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->jsonResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function provider(ProviderRequest $providerRequest, $provider)
    {
        $validated = $this->validateProvider($provider);

        if (!is_null($validated)) {
            return $validated;
        }

        try {
            $socialUser = Socialite::driver($provider)->userFromToken($providerRequest->token);
        } catch (ClientException $exception) {
            return $this->errorResponse(__('Invalid credentials provided.'), 401);
        }

        $user = User::firstOrCreate(
            [
                'email' => $socialUser->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $socialUser->getName(),
                'username' => $socialUser->getNickname(),
                'status_id' => Status::enabled()->value('id'),
            ]
        );

        $user->syncRoles(Role::user()->value('id'));

        $user->socialNetworks()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ],
            [
                'avatar' => $socialUser->getAvatar()
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->jsonResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            return $this->errorResponse(__('Please login using facebook or google'), 400);
        }
    }
}
