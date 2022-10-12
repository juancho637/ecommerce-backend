<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Role;
use App\Models\Status;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class ProductIndexController extends ApiController
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     summary="List of products",
     *     operationId="getAllProducts",
     *     tags={"Products"},
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
     *     @OA\Response(
     *         response="200",
     *         description="success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             ),
     *         ),
     *     ),
     * )
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));
        $products = $this->product;

        if ($request->search) {
            $products = $products->search($request->search)
                ->query(function (Builder $query) use ($includes) {
                    $query->byRole();

                    $this->eagerLoadIncludes($query, $includes);
                })
                ->get();
        } else {
            $products = $products->query()->byRole();
            $products = $this->eagerLoadIncludes($products, $includes)->get();
        }

        return $this->showAll($products);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        $user = auth('sanctum')->user();

        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('photos', $includes)) {
            $query->with('photos');
        }

        if (in_array('category', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $query->with(['category']);
            } else {
                $query->with(['category' => function ($query) {
                    $query->whereHas('status', function ($query) {
                        $query->where('name', Status::ENABLED);
                    });
                }]);
            }
        }

        if (in_array('tags', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $query->with(['tags']);
            } else {
                $query->with(['tags' => function ($query) {
                    $query->whereHas('status', function ($query) {
                        $query->where('name', Status::ENABLED);
                    });
                }]);
            }
        }

        if (in_array('product_attribute_options', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $query->with(['productAttributeOptions.productAttribute']);
            } else {
                $query->with([
                    'productAttributeOptions.productAttribute' => function ($query) {
                        $query->whereHas('status', function ($query) {
                            $query->where('name', Status::ENABLED);
                        });
                    }
                ]);
            }
        }

        return $query;
    }
}
