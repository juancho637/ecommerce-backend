<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\LogoutRequest;

class LogoutController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     summary="Logout authenticated user",
     *     description="<strong>Method:</strong> logoutAuthUser",
     *     operationId="logoutAuthUser",
     *     tags={"Auth"},
     *     security={ {"sanctum": {}} },
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
     *                 ref="#/components/schemas/LogoutRequest",
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
     *         response="401",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/AuthenticationException",
     *         ),
     *     ),
     * )
     */
    public function __invoke(LogoutRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('sanctum')->user();

            if (filter_var($request->all, FILTER_VALIDATE_BOOLEAN)) {
                $user->tokens()->delete();
            } else {
                $user->currentAccessToken()->delete();
            }
            DB::commit();

            return $this->showMessage(__('Logged out'));
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
