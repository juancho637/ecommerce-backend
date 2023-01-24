<?php

namespace App\Http\Controllers\Api\Product\ProductStock;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Actions\ProductStock\UpdateProductStock;
use App\Http\Requests\Api\Product\ProductStock\UpdateProductProductStockRequest;

class ProductProductStockUpdateController extends ApiController
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
     *     path="/api/v1/products/{product}/product_stocks",
     *     summary="Update product stocks by product",
     *     description="<strong>Method:</strong> updateProductStockByProduct<br/><strong>Includes:</strong> status, product, product_attribute_options, images",
     *     operationId="updateProductStockByProduct",
     *     tags={"Product stocks by product"},
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
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/StoreProductStockDTO",
     *             )
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
    public function __invoke(
        UpdateProductProductStockRequest $request,
        Product $product,
        ProductStock $productStock
    ) {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->productStock = app(UpdateProductStock::class)(
                $this->productStock->setUpdate($request, $product->type),
                $product,
                $productStock,
            );
            DB::commit();

            return $this->showOne(
                $this->productStock->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
