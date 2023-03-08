<?php

namespace App\Http\Controllers\Api\ProductAttributeOption;

use Illuminate\Support\Facades\DB;
use App\Models\ProductAttributeOption;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProductAttributeOption\UpdateProductAttributeOptionRequest;

class ProductAttributeOptionUpdateController extends ApiController
{
    private $productAttributeOption;

    public function __construct(ProductAttributeOption $productAttributeOption)
    {
        $this->productAttributeOption = $productAttributeOption;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update,productAttributeOption')->only('__invoke');
    }

    /**
     * @OA\Put(
     *     path="/api/v1/product_attribute_options/{productAttributeOption}",
     *     summary="Update product attribute",
     *     description="<strong>Method:</strong> updateProductAttributeOption<br/><strong>Includes:</strong> status, product_attribute",
     *     operationId="updateProductAttributeOption",
     *     tags={"Product attribute options"},
     *     security={ {"sanctum": {}} },
     *     @OA\Parameter(
     *         name="productAttributeOption",
     *         description="Id of product attribute option",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="number"
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
     *                 ref="#/components/schemas/UpdateProductAttributeOptionRequest",
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
     *                 ref="#/components/schemas/ProductAttributeOption",
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
        UpdateProductAttributeOptionRequest $request,
        ProductAttributeOption $productAttributeOption
    ) {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->productAttributeOption = $productAttributeOption->setUpdate($request);
            $this->productAttributeOption->save();
            DB::commit();

            return $this->showOne(
                $this->productAttributeOption->scopeWithEagerLoading(
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
