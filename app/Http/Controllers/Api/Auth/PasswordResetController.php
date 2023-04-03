<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\PasswordResetRequest;

class PasswordResetController extends ApiController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;

        // $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/password-reset",
     *     summary="Reset user password",
     *     description="<strong>Method:</strong> passwordReset",
     *     operationId="passwordReset",
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
     *                 ref="#/components/schemas/PasswordResetRequest",
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
     *         response="403",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/AuthorizationException",
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ModelNotFoundException",
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
    public function __invoke(PasswordResetRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->user = $this->user->where('email', $request->email)->first();

            if (!$this->user || !$this->user->email) {
                throw new \Exception(__('Incorrect email address.'), Response::HTTP_NOT_FOUND);
            }

            $resetPassword = PasswordReset::where('email', $this->user->email)->first();

            if (!$resetPassword || $resetPassword->token !== $request->token) {
                throw new \Exception(__('Token mismatch.'), Response::HTTP_NOT_FOUND);
            }

            if (Carbon::now()->gt($resetPassword->updated_at->addMinute(10))) {
                throw new \Exception(__('Expired token.'), Response::HTTP_BAD_REQUEST);
            }

            $this->user->update([
                'password' => Hash::make($request->password)
            ]);

            $this->user->tokens()->delete();
            $resetPassword->delete();

            $token = $this->user->createToken('auth_token')->plainTextToken;
            DB::commit();

            return $this->jsonResponse([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
