<?php

namespace App\Http\Controllers\Api\Category;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Actions\Category\UpdateCategory;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Category\UpdateCategoryRequest;

class CategoryUpdateController extends ApiController
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update,category')->only('__invoke');
    }

    /**
     * @OA\Put(
     *     path="/api/v1/categories/{category}",
     *     summary="Update category",
     *     operationId="updateCategory",
     *     tags={"Categories"},
     *     security={ {"sanctum": {}} },
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
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/UpdateCategoryRequest",
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
     *                 ref="#/components/schemas/Category",
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
    public function __invoke(UpdateCategoryRequest $request, Category $category)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->category = app(UpdateCategory::class)(
                $category,
                $this->category->setUpdate($request)
            );
            DB::commit();

            return $this->showOne(
                $this->category->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
