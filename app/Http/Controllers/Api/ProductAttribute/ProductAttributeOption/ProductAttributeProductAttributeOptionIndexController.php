<?php

namespace App\Http\Controllers\Api\ProductAttribute\ProductAttributeOption;

use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use App\Http\Controllers\Api\ApiController;

class ProductAttributeProductAttributeOptionIndexController extends ApiController
{
    private $productAttributeOptions;

    public function __construct(ProductAttributeOption $productAttributeOption)
    {
        $this->productAttributeOptions = $productAttributeOption;

        $this->middleware('auth:sanctum');

        $this->middleware('can:view-any,' . ProductAttributeOption::class)->only('__invoke');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product_attributes/{productAttribute}/product_attribute_options",
     *     summary="List of product attribute options by product attribute",
     *     description="<strong>Method:</strong> getAllProductAttributeOptionsByProductAttribute<br/><strong>Includes:</strong> status, productAttribute",
     *     operationId="getAllProductAttributeOptionsByProductAttribute",
     *     tags={"Product attribute options by product attribute"},
     *     security={ {"sanctum": {}} },
     *     @OA\Parameter(
     *         name="productAttribute",
     *         description="Id of product attribute",
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
     *         response="404",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ModelNotFoundException",
     *         ),
     *     ),
     * )
     */
    public function __invoke(Request $request, ProductAttribute $productAttribute)
    {
        $includes = explode(',', $request->get('include', ''));

        $this->productAttributeOptions = $productAttribute->productAttributeOptions->toQuery();
        $this->productAttributeOptions = $this->productAttributeOptions->withEagerIncludes($includes)->get();

        return $this->showAll($this->productAttributeOptions);
    }
}
