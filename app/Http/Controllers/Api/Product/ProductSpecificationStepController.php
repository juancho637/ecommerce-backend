<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\ProductSpecification;
use App\Http\Controllers\Api\ApiController;
use App\Actions\Product\StoreProductSpecificationStep;
use App\Http\Requests\Api\Product\StoreProductSpecificationStepRequest;

class ProductSpecificationStepController extends ApiController
{
    private $productSpecification;

    public function __construct(ProductSpecification $productSpecification)
    {
        $this->productSpecification = $productSpecification;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . ProductSpecification::class)->only('__invoke');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/products/{product}/specifications_step",
     *     summary="Save specifications step by product",
     *     description="<strong>Method:</strong> saveProductSpecificationsStepByProduct<br/><strong>Includes:</strong> status, product",
     *     operationId="saveProductSpecificationsStepByProduct",
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
     *                 ref="#/components/schemas/StoreProductSpecificationStepRequest",
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
    public function __invoke(StoreProductSpecificationStepRequest $request, Product $product)
    {
        $includes = explode(',', $request->get('include', ''));

        try {
            $this->productSpecification = app(StoreProductSpecificationStep::class)(
                $product->setCreateProductSpecificationStep($request),
                $product,
            )->withEagerLoading($includes)->get();
            DB::commit();

            return ($this->showAll($this->productSpecification))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
