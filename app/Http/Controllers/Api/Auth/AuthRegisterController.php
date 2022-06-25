<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\Status;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\RegisterAuthRequest;

class AuthRegisterController extends ApiController
{
    /**
     * Registro
     * 
     * Registro de usuarios en la aplicación.
     * 
     * @group Auth
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User with=status
     */
    public function __invoke(RegisterAuthRequest $request)
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
}
