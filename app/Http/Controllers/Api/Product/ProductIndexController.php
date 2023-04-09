<?php

namespace App\Http\Controllers\Api\Product;

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
     *     description="<strong>Method:</strong> getAllProducts<br/><strong>Includes:</strong> status, images, stock_images, category, tags, product_attribute_options, product_stocks",
     *     operationId="getAllProducts",
     *     tags={"Products"},
     *     @OA\Parameter(ref="#/components/parameters/relationships--include"),
     *     @OA\Parameter(ref="#/components/parameters/filter--search"),
     *     @OA\Parameter(ref="#/components/parameters/pagination--per_page"),
     *     @OA\Parameter(ref="#/components/parameters/pagination--page"),
     *     @OA\Parameter(ref="#/components/parameters/filter--sort_by"),
     *     @OA\Parameter(ref="#/components/parameters/localization--lang"),
     * 
     *     @OA\Parameter(ref="#/components/parameters/product--id"),
     *     @OA\Parameter(ref="#/components/parameters/product--status"),
     *     @OA\Parameter(ref="#/components/parameters/product--category"),
     *     @OA\Parameter(ref="#/components/parameters/product--type"),
     *     @OA\Parameter(ref="#/components/parameters/product--name"),
     *     @OA\Parameter(ref="#/components/parameters/product--price"),
     *     @OA\Parameter(ref="#/components/parameters/product--tax"),
     *     @OA\Parameter(ref="#/components/parameters/product--slug"),
     *     @OA\Parameter(ref="#/components/parameters/product--description"),
     *     @OA\Parameter(ref="#/components/parameters/product--is_variable"),
     *     @OA\Parameter(ref="#/components/parameters/product--amount_viewed"),
     *     @OA\Parameter(ref="#/components/parameters/product--quantity_sold"),
     * 
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
            $this->product = $this->product->search($request->search)
                ->query(function (Builder $query) use ($includes) {
                    $query->byRole()
                        ->withEagerLoading($includes);
                })
                ->get();
        } else {
            $this->product = $this->product->query()
                ->byRole()
                ->withEagerLoading($includes)
                ->get();
        }

        return $this->showAll($this->product);
    }
}
