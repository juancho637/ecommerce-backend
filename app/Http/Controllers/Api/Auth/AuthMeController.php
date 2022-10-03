<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

class AuthMeController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/auth/me",
     *     summary="Show authenticated user info",
     *     operationId="getAuthUser",
     *     tags={"Auth"},
     *     security={ {"sanctum": {}} },
     *     @OA\Response(
     *         response="200",
     *         description="success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/User",
     *             ),
     *         ),
     *     ),
     * )
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));
        $user = auth('sanctum')->user();

        return $this->showOne(
            $user->loadEagerLoadIncludes($includes)
        );
    }
}
