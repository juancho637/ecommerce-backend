<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
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
}
