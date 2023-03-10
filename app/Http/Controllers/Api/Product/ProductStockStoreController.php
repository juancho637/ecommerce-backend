<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\ApiController;
use App\Actions\Product\StoreProductStockStep;
use App\Http\Requests\Api\Product\StoreProductStockRequest;

class ProductStockStoreController extends ApiController
{
    private $productStock;

    public function __construct(ProductStock $productStock)
    {
        $this->productStock = $productStock;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . ProductStock::class)->only('__invoke');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/products/{product}/stocks",
     *     summary="Save product stock step by product",
     *     description="<strong>Method:</strong> saveProductStockStepByProduct<br/><strong>Includes:</strong> status, product, images, product_attribute_options, product_attribute_options.product_attribute",
     *     operationId="saveProductStockStepByProduct",
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
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/StoreProductStockRequest",
     *             )
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
    public function __invoke(StoreProductStockRequest $request, Product $product)
    {
        $includes = explode(',', $request->get('include', ''));

        try {
            $this->productStock = app(StoreProductStockStep::class)(
                $product->setCreateProductStockStep($request),
                $product,
            )->withEagerLoading($includes)->get();

            return ($this->showAll($this->productStock))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}
