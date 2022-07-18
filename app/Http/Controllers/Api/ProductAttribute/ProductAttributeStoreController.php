<?php

namespace App\Http\Controllers\Api\ProductAttribute;

use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProductAttribute\StoreProductAttributeRequest;

class ProductAttributeStoreController extends ApiController
{
    private $productAttribute;

    public function __construct(ProductAttribute $productAttribute)
    {
        $this->productAttribute = $productAttribute;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . ProductAttribute::class)->only('__invoke');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/product_attributes",
     *     summary="Save product attribute",
     *     operationId="saveProductAttribute",
     *     tags={"Product attributes"},
     *     security={ {"sanctum": {}} },
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/StoreProductAttributeRequest",
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
     *         response="422",
     *         description="fail",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ValidationException",
     *         ),
     *     ),
     * )
     */
    public function __invoke(StoreProductAttributeRequest $request)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->productAttribute = $this->productAttribute->create(
                $this->productAttribute->setCreate($request)
            );
            DB::commit();

            return $this->showOne(
                $this->productAttribute
                    ->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
