<?php

namespace App\Http\Controllers\Api\ProductStock;

use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

class ProductStockShowController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('can:view,productStock')->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product_stocks/{productStock}",
     *     summary="Show product stock by id",
     *     description="<strong>Method:</strong> getProductStockById<br/><strong>Includes:</strong> status, product, images, product_attribute_options, product_attribute_options.product_attribute",
     *     operationId="getProductStockById",
     *     tags={"Product stocks"},
     *     @OA\Parameter(
     *         name="productStock",
     *         description="Id of product stock",
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
     *                 ref="#/components/schemas/ProductStock",
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
    public function __invoke(Request $request, ProductStock $productStock)
    {
        $includes = explode(',', $request->get('include', ''));

        return $this->showOne(
            $productStock->scopeWithEagerLoading(
                query: null,
                includes: $includes,
                type: 'load'
            )
        );
    }
}
