<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Auth\AuthenticationException;
use App\Http\Requests\Api\Auth\LoginAuthRequest;

class AuthLoginController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Sign in a user",
     *     description="<strong>Method:</strong> signIn",
     *     operationId="signIn",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *         name="lang",
     *         description="Code of language",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/LoginAuthRequest",
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
     *             @OA\Property(property="error", type="string", example="Invalid login"),
     *             @OA\Property(property="code", type="number", example=401),
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
    public function __invoke(LoginAuthRequest $request)
    {
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (!Auth::attempt([
            $fieldType => $request->username,
            'password' => $request->password
        ])) {
            throw new AuthenticationException(__('Invalid login'));
        }

        $user = User::where($fieldType, $request->username)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->jsonResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }
}
