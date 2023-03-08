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
     *     description="<strong>Method:</strong> getAllProductAttributeOptions<br/><strong>Includes:</strong> status, product_attribute",
     *     operationId="getAllProductAttributeOptions",
     *     tags={"Product attribute options"},
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

        $this->productAttributeOption = $this->productAttributeOption
            ->query()
            ->withEagerLoading($includes)
            ->get();

        return $this->showAll($this->productAttributeOption);
    }
}
