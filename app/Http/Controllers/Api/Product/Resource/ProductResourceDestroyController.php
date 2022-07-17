<?php

namespace App\Http\Controllers\Api\Product\Resource;

use App\Models\Product;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;

class ProductResourceDestroyController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('can:delete,product')->only('__invoke');
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/products/{product}/photos/{resource}",
     *     summary="Delete a product photo",
     *     operationId="deleteProductPhoto",
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
     *         name="resource",
     *         description="Id of photo",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Resource",
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
    public function __invoke(Request $request, Product $product, Resource $resource)
    {
        if (!$product->photos->contains($resource)) {
            throw new \Exception(__("The file does not belong to the product"));
        }

        DB::beginTransaction();
        try {
            $resource->delete();
            $resource->deleteFile($resource->path);
            DB::commit();

            return $this->showOne($resource);
        } catch (\Exception $exception) {
            DB::rollback();
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
    }
}
