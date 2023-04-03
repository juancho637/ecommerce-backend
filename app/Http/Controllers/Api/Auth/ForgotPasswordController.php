<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Notifications\User\ResetPassword;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;

class ForgotPasswordController extends ApiController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;

        // $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/forgot-password",
     *     summary="Forgot password",
     *     description="<strong>Method:</strong> forgotPassword",
     *     operationId="forgotPassword",
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
     *                 ref="#/components/schemas/ForgotPasswordRequest",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="success",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ResponseMessage",
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
    public function __invoke(ForgotPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->user = $this->user->where('email', $request->email)->first();

            if (!$this->user || !$this->user->email) {
                throw new \Exception(__('Incorrect email address'), Response::HTTP_NOT_FOUND);
            }

            $resetPasswordToken = Str::random(6);

            if (!$userPassReset = PasswordReset::where('email', $this->user->email)->first()) {
                PasswordReset::create([
                    'email' => $request->email,
                    'token' => $resetPasswordToken
                ]);
            } else {
                $userPassReset->update([
                    'token' => $resetPasswordToken
                ]);
            }

            $this->user->notify(new ResetPassword($resetPasswordToken));
            DB::commit();

            return $this->showMessage(__('A code has been sent to your Email.'));
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
