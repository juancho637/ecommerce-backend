<?php

namespace App\Http\Controllers\Api\ProductSpecification;

use Illuminate\Support\Facades\DB;
use App\Models\ProductSpecification;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProductSpecification\UpdateProductSpecificationRequest;

class ProductSpecificationUpdateController extends ApiController
{
    private $productSpecification;

    public function __construct(ProductSpecification $productSpecification)
    {
        $this->productSpecification = $productSpecification;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update,productSpecification')->only('__invoke');
    }

    /**
     * @OA\Put(
     *     path="/api/v1/product_specifications/{productSpecification}",
     *     summary="Update product specification",
     *     operationId="updateProductSpecification",
     *     tags={"Product specifications"},
     *     security={ {"sanctum": {}} },
     *     @OA\Parameter(
     *         name="productSpecification",
     *         description="Id of product specification",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/UpdateProductSpecificationRequest",
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
     *                 ref="#/components/schemas/ProductSpecification",
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
        UpdateProductSpecificationRequest $request,
        ProductSpecification $productSpecification
    ) {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->productSpecification = $productSpecification->setUpdate($request);
            $this->productSpecification->save();
            DB::commit();

            return $this->showOne(
                $this->productSpecification
                    ->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
