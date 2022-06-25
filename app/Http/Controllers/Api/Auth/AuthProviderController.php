<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\Status;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\ProviderAuthRequest;

class AuthProviderController extends ApiController
{
    /**
     * Login/registro redes sociales
     * 
     * Login y/o registro mediante redes sociales en la aplicación.
     * 
     * @urlParam provider string required Proveedor de la red social. Example: facebook
     * 
     * @group Auth
     * @response scenario=success {
     *  "access_token": <token>,
     *  "token_type": "Bearer"
     * }
     */
    public function __invoke(ProviderAuthRequest $request, $provider)
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
