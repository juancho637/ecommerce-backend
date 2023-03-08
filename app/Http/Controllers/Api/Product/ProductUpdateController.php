<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product;
use App\Actions\Product\UpdateProduct;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Product\UpdateProductRequest;

class ProductUpdateController extends ApiController
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update,product')->only('__invoke');
    }

    /**
     * @OA\Put(
     *     path="/api/v1/products/{product}/general",
     *     summary="Update product general information", 
     *     description="<strong>Method:</strong> updateProductGeneral<br/><strong>Includes:</strong> status, images, stock_images, category, tags, product_attribute_options, product_stocks",
     *     operationId="updateProductGeneral",
     *     tags={"Products"},
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
     *         name="lang",
     *         description="Code of language",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/UpdateProductGeneralRequest")
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
     *         response="422",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ValidationException",
     *         ),
     *     ),
     * )
     */
    public function __invoke(UpdateProductRequest $request, Product $product)
    {
        $includes = explode(',', $request->get('include', ''));

        try {
            $this->product = app(UpdateProduct::class)(
                $product->setUpdate($request),
                $product,
            );

            return $this->showOne(
                $this->product->scopeWithEagerLoading(
                    query: null,
                    includes: $includes,
                    type: 'load'
                )
            );
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}
