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
     *     description="<strong>Method:</strong> getAuthUser<br/><strong>Includes:</strong> status, roles, social_networks",
     *     operationId="getAuthUser",
     *     tags={"Auth"},
     *     security={ {"sanctum": {}} },
     *     @OA\Parameter(
     *         name="include",
     *         description="Relationships of resource",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lang",
     *         description="Code of language",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
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
            $user->scopeWithEagerLoading(
                query: null,
                includes: $includes
            )
        );
    }
}
