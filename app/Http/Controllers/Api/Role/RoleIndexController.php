<?php

namespace App\Http\Controllers\Api\Role;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

class RoleIndexController extends ApiController
{
    private $role;

    public function __construct(Role $role)
    {
        $this->role = $role;

        $this->middleware('auth:sanctum');

        $this->middleware('can:view-any,' . Role::class)->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/roles",
     *     summary="List of roles",
     *     description="<strong>Method:</strong> getAllRoles",
     *     operationId="getAllRoles",
     *     tags={"Roles"},
     *     security={ {"sanctum": {}} },
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
     *                 @OA\Items(ref="#/components/schemas/Role")
     *             ),
     *             @OA\Property(
     *                 type="object",
     *                 property="meta",
     *                 ref="#/components/schemas/Pagination",
     *             ),
     *         ),
     *     ),
     * )
     */
    public function __invoke(Request $request)
    {
        $this->role = $this->role->query()->get();

        return $this->showAll($this->role);
    }
}
