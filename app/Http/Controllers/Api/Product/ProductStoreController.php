<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Actions\Product\StoreProduct;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Product\StoreProductRequest;

class ProductStoreController extends ApiController
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . Product::class)->only('__invoke');
    }

    /**
     * Guardar producto
     * 
     * Guarda un producto en la aplicación.
     * 
     * @group Productos
     * @authenticated
     * @apiResource App\Http\Resources\ProductResource
     * @apiResourceModel App\Models\Product with=status,category,tags,productAttributeOptions,photos
     */
    public function __invoke(StoreProductRequest $request)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->product = app(StoreProduct::class)(
                $this->product->setCreate($request)
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
