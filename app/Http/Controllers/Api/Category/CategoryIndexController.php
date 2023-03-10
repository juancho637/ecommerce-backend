<?php

namespace App\Http\Controllers\Api\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class CategoryIndexController extends ApiController
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="List of categories",
     *     description="<strong>Method:</strong> getAllCategories<br/><strong>Includes:</strong> status, image, children",
     *     operationId="getAllCategories",
     *     tags={"Categories"},
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
     *     @OA\Parameter(
     *         name="exclude_id",
     *         description="Category id to exclude",
     *         required=false,
     *         in="query",
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
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(ref="#/components/schemas/Category")
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
        $includes = explode(',', $request->get('include', ''));

        if ($request->search) {
            $this->category = $this->category->search($request->search)
                ->query(function (Builder $query) use ($includes) {
                    $query->byRole()
                        ->withEagerLoading($includes);
                })
                ->get();
        } else {
            $this->category = $this->category->query()
                ->byRole()
                ->includeHasChildren(in_array('children', $includes))
                ->withEagerLoading($includes)
                ->get();
        }

        if ($excludeId = $request->get('exclude_id', false)) {
            $contains = $this->category->where('id', $excludeId);

            if ($contains->count() > 0) {
                $this->category->forget($contains->keys()->first());
            } else {
                $this->category = $this->filterChildrenId($this->category, $excludeId);
            }
        }

        return $this->showAll($this->category);
    }

    public function filterChildrenId($children, $id)
    {
        $children->each(function ($child) use ($id) {
            if (isset($child->children) && $child->children->count() > 0) {
                $contains = $child->children->where('id', $id);

                if ($contains->count() > 0) {
                    $child->children->forget($contains->keys()->first());
                }

                $this->filterChildrenId($child->children, $id);
            }
        });

        return $children;
    }
}
