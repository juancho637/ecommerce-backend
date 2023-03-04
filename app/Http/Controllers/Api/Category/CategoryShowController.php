<?php

namespace App\Http\Controllers\Api\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryShowController extends ApiController
{
    public function __construct()
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{category}",
     *     summary="Show category by id",
     *     operationId="getCategoryById",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="category",
     *         description="Id of category",
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
     *     @OA\Response(
     *         response="200",
     *         description="success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Category",
     *             ),
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
    public function __invoke(Request $request, Category $category)
    {
        $includes = explode(',', $request->get('include', ''));

        if ($category->validByRole()) {
            return $this->showOne(
                $category->loadEagerLoadIncludes($includes)
            );
        }

        throw new ModelNotFoundException;
    }
}
