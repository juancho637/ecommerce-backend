<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\LoginAuthRequest;

class AuthLoginController extends ApiController
{
    /**
     * Login
     * 
     * Login en la aplicación.
     * 
     * @group Auth
     * @response scenario=success {
     *  "access_token": <token>,
     *  "token_type": "Bearer"
     * }
     */
    public function __invoke(LoginAuthRequest $request)
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
}
