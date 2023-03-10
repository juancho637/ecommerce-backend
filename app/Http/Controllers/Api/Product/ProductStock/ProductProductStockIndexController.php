<?php

namespace App\Http\Controllers\Api\Product\ProductStock;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

class ProductProductStockIndexController extends ApiController
{
    private $productStocks;

    public function __construct(ProductStock $productStock)
    {
        $this->productStocks = $productStock;

        $this->middleware('auth:sanctum');

        $this->middleware('can:view-any,' . ProductStock::class)->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products/{product}/product_stocks",
     *     summary="List of product stocks by product",
     *     description="<strong>Method:</strong> getAllProductStocksByProduct<br/><strong>Includes:</strong> status, product, images, product_attribute_options, product_attribute_options.product_attribute",
     *     operationId="getAllProductStocksByProduct",
     *     tags={"Product stocks"},
     *     security={ {"sanctum": {}} },
     *     @OA\Parameter(
     *         name="product",
     *         description="Id of product",
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
     *                 @OA\Items(ref="#/components/schemas/ProductStock")
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
    public function __invoke(Request $request, Product $product)
    {
        $includes = explode(',', $request->get('include', ''));

        $this->productStocks = $product->productStocks->toQuery()
            ->withEagerLoading($includes)
            ->get();

        return $this->showAll($this->productStocks);
    }
}
