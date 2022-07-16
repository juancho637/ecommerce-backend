<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Role;
use App\Models\User;
use App\Models\Status;
use Illuminate\Http\Response;
use App\Models\SocialNetwork;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\ProviderAuthRequest;

class AuthProviderController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/{provider}",
     *     summary="Sign in or sign up a user with social network",
     *     operationId="signInOrSignUpWithSocialNetwork",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *         name="provider",
     *         description="Provider name",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/ProviderAuthRequest",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="access_token",
     *                 type="string",
     *                 default="<token>",
     *             ),
     *             @OA\Property(
     *                 property="token_type",
     *                 type="string",
     *                 default="Bearer",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/BadRequestException",
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/BadRequestException",
     *         ),
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ValidationException",
     *         ),
     *     ),
     * )
     */
    public function __invoke(ProviderAuthRequest $request, $provider)
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->userFromToken($request->token);
        } catch (ClientException $exception) {
            throw new \Exception(__('Invalid credentials provided'), Response::HTTP_UNAUTHORIZED);
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
        if (!in_array($provider, SocialNetwork::PROVIDERS)) {
            throw new \Exception(__('Please login using facebook or google'));
        }
    }
}
