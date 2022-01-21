<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\Status;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\LoginAuthRequest;
use App\Http\Requests\Api\Auth\ProviderAuthRequest;
use App\Http\Requests\Api\Auth\RegisterAuthRequest;

class AuthController extends ApiController
{
    public function login(LoginAuthRequest $request)
    {
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (!Auth::attempt([
            $fieldType => $request->username,
            'password' => $request->password
        ])) {
            return $this->errorResponse(__('Invalid login'), Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where($fieldType, $request->username)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->jsonResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function register(RegisterAuthRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'status_id' => Status::enabled()->value('id'),
        ]);
        $user->syncRoles(Role::user()->value('id'));

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->jsonResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function provider(ProviderAuthRequest $request, $provider)
    {
        $validated = $this->validateProvider($provider);

        if (!is_null($validated)) {
            return $validated;
        }

        try {
            $socialUser = Socialite::driver($provider)->userFromToken($request->token);
        } catch (ClientException $exception) {
            return $this->errorResponse(__('Invalid credentials provided.'), Response::HTTP_UNAUTHORIZED);
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
            return $this->errorResponse(__('Please login using facebook or google'));
        }
    }
}
