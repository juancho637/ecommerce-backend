<?php

namespace App\Http\Controllers\Api\ProductSpecification;

use Illuminate\Http\Request;
use App\Models\ProductSpecification;
use App\Http\Controllers\Api\ApiController;

class ProductSpecificationShowController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('can:view,productSpecification')->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product_specifications/{productSpecification}",
     *     summary="Show product specification by id",
     *     operationId="getProductSpecificationById",
     *     tags={"Product specifications"},
     *     security={ {"sanctum": {}} },
     *     @OA\Parameter(
     *         name="productSpecification",
     *         description="Id of product specification",
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
     *                 ref="#/components/schemas/ProductSpecification",
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
     *     @OA\Response(
     *         response="404",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ModelNotFoundException",
     *         ),
     *     ),
     * )
     */
    public function __invoke(Request $request, ProductSpecification $productSpecification)
    {
        $includes = explode(',', $request->get('include', ''));

        return $this->showOne(
            $productSpecification->loadEagerLoadIncludes($includes)
        );
    }
}
