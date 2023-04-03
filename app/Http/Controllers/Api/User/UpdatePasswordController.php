<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Actions\User\UpdatePassword;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\User\UpdatePasswordRequest;

class UpdatePasswordController extends ApiController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update-password,user')->only('__invoke');
    }

    /**
     * @OA\Put(
     *     path="/api/v1/users/{user}/password",
     *     summary="Update user password",
     *     description="<strong>Method:</strong> updateUserPassword<br/><strong>Includes:</strong> status, roles, social_networks",
     *     operationId="updateUserPassword",
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
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/UpdatePasswordRequest",
     *             )
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
     *     @OA\Response(
     *         response="403",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/AuthorizationException",
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
    public function __invoke(UpdatePasswordRequest $request, User $user)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->user = app(UpdatePassword::class)(
                $user->setUpdatePassword($request),
                $user,
            );
            DB::commit();

            return $this->showOne(
                $this->user->scopeWithEagerLoading(
                    query: null,
                    includes: $includes,
                )
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
