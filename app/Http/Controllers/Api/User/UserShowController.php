<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

class UserShowController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('can:view,user')->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/{user}",
     *     summary="Show user by id",
     *     operationId="getUserById",
     *     tags={"Users"},
     *     security={ {"sanctum": {}} },
     *     @OA\Parameter(
     *         name="user",
     *         description="Id of user",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="number"
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
     *     @OA\Response(
     *         response="401",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/AuthenticationException",
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
     * )
     */
    public function __invoke(Request $request, User $user)
    {
        $includes = explode(',', $request->get('include', ''));

        return $this->showOne(
            $user->loadEagerLoadIncludes($includes)
        );
    }
}
