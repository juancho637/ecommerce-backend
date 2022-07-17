<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductShowController extends ApiController
{
    public function __construct()
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products/{product}",
     *     summary="Show product by id",
     *     operationId="getProductById",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="product",
     *         description="Id of product",
     *         required=true,
     *         in="path",
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
     *                 property="data",
     *                 ref="#/components/schemas/Product",
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
    public function __invoke(Request $request, Product $product)
    {
        $includes = explode(',', $request->get('include', ''));

        if ($product->validByRole()) {
            return $this->showOne(
                $product->loadEagerLoadIncludes($includes)
            );
        }

        throw new ModelNotFoundException;
    }
}
