<?php

namespace App\Http\Controllers\Api\ProductAttributeOption;

use Illuminate\Http\Request;
use App\Models\ProductAttributeOption;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class ProductAttributeOptionIndexController extends ApiController
{
    private $productAttributeOption;

    public function __construct(ProductAttributeOption $productAttributeOption)
    {
        $this->productAttributeOption = $productAttributeOption;

        $this->middleware('auth:sanctum');

        $this->middleware('can:view-any,' . ProductAttributeOption::class)->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product_attribute_options",
     *     summary="List of product attribute options",
     *     operationId="getAllProductAttributeOptions",
     *     tags={"Product attribute options"},
     *     security={ {"sanctum": {}} },
     *     @OA\Response(
     *         response="200",
     *         description="success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 type="array",
     *                 property="data",
     *                 @OA\Items(ref="#/components/schemas/ProductAttributeOption")
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

        $productAttributeOptions = $this->productAttributeOption->query();
        $productAttributeOptions = $this->eagerLoadIncludes($productAttributeOptions, $includes)->get();

        return $this->showAll($productAttributeOptions);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('product_attribute', $includes)) {
            $query->with('productAttribute');
        }

        return $query;
    }
}
