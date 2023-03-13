<?php

namespace App\Http\Controllers\Api\ProductAttribute;

use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;

class ProductAttributeDestroyController extends ApiController
{
    private $productAttribute;

    public function __construct(ProductAttribute $productAttribute)
    {
        $this->productAttribute = $productAttribute;

        $this->middleware('auth:sanctum');

        $this->middleware('can:delete,productAttribute')->only('__invoke');
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/product_attributes/{productAttribute}",
     *     summary="Delete product attribute",
     *     description="<strong>Method:</strong> deleteProductAttribute<br/><strong>Includes:</strong> status, product_attribute_options",
     *     operationId="deleteProductAttribute",
     *     tags={"Product attributes"},
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
     *                 ref="#/components/schemas/ProductAttribute",
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

        DB::beginTransaction();
        try {
            $this->productAttribute = $productAttribute->setDelete();
            $this->productAttribute->save();
            DB::commit();

            return $this->showOne(
                $this->productAttribute->scopeWithEagerLoading(
                    query: null,
                    includes: $includes,
                    type: 'load'
                )
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
