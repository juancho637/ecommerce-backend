<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class UserIndexController extends ApiController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;

        $this->middleware('auth:sanctum');

        $this->middleware('can:view-any,' . User::class)->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="List of users",
     *     operationId="getAllUser",
     *     tags={"Users"},
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
     *         name="search",
     *         description="String to search",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         description="Number of resources per page",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         description="Number of current page",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         description="Name of field to sort",
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
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(ref="#/components/schemas/User")
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
     * )
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $users = $this->user->query();
        $users = $this->eagerLoadIncludes($users, $includes)->get();

        return $this->showAll($users);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('roles', $includes)) {
            $query->with('roles');
        }

        if (in_array('socialNetworks', $includes)) {
            $query->with('socialNetworks');
        }

        return $query;
    }
}
