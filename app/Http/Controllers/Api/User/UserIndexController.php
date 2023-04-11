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
     *     description="<strong>Method:</strong> getAllUser<br/><strong>Includes:</strong> status, roles, social_networks",
     *     operationId="getAllUser",
     *     tags={"Users"},
     *     security={ {"sanctum": {}} },
     *     @OA\Parameter(ref="#/components/parameters/relationships--include"),
     *     @OA\Parameter(ref="#/components/parameters/filter--search"),
     *     @OA\Parameter(ref="#/components/parameters/pagination--per_page"),
     *     @OA\Parameter(ref="#/components/parameters/pagination--page"),
     *     @OA\Parameter(ref="#/components/parameters/filter--sort_by"),
     *     @OA\Parameter(ref="#/components/parameters/localization--lang"),
     * 
     *     @OA\Parameter(ref="#/components/parameters/user--id"),
     *     @OA\Parameter(ref="#/components/parameters/user--status"),
     *     @OA\Parameter(ref="#/components/parameters/user--name"),
     *     @OA\Parameter(ref="#/components/parameters/user--email"),
     *     @OA\Parameter(ref="#/components/parameters/user--username"),
     * 
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
     *             @OA\Property(
     *                 type="object",
     *                 property="meta",
     *                 ref="#/components/schemas/Pagination",
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

        if ($request->search) {
            $this->user = $this->user->search($request->search)
                ->query(function (Builder $query) use ($includes) {
                    $query->withEagerLoading($includes);
                })
                ->get();
        } else {
            $this->user = $this->user
                ->query()
                ->withEagerLoading($includes)
                ->get();
        }

        return $this->showAll($this->user);
    }
}
