<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
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
     * Actualizar producto
     * 
     * Actualiza el producto indicado por el id.
     * 
     * @group Productos
     * @authenticated
     * @apiResource App\Http\Resources\ProductResource
     * @apiResourceModel App\Models\Product with=status,category,tags,productAttributeOptions,photos
     * 
     * @urlParam id int required Id del product.
     */
    public function __invoke(UpdateProductRequest $request, Product $product)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->product = app(UpdateProduct::class)(
                $product,
                $this->product->setUpdate($request)
            );
            DB::commit();

            return $this->showOne(
                $this->product->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
