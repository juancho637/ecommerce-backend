<?php

namespace App\Http\Controllers\Api\ProductSpecification;

use Illuminate\Http\Request;
use App\Models\ProductSpecification;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class ProductSpecificationIndexController extends ApiController
{
    private $productSpecification;

    public function __construct(ProductSpecification $productSpecification)
    {
        $this->productSpecification = $productSpecification;

        $this->middleware('auth:sanctum');

        $this->middleware('can:view-any,' . ProductSpecification::class)->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product_specifications",
     *     summary="List of product specifications",
     *     operationId="getAllProductSpecification",
     *     tags={"Product specifications"},
     *     security={ {"sanctum": {}} },
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
     *                 @OA\Items(ref="#/components/schemas/ProductSpecification")
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
     * )
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $productSpecifications = $this->productSpecification->query();
        $productSpecifications = $this->eagerLoadIncludes($productSpecifications, $includes)->get();

        return $this->showAll($productSpecifications);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('product', $includes)) {
            $query->with('product');
        }

        return $query;
    }
}
