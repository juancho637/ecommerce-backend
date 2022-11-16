<?php

namespace App\Http\Controllers\Api\ProductStock;

use App\Models\ProductStock;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProductStock\StoreProductStockRequest;

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
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreProductStockRequest $request)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->productStock = $this->productStock->create(
                $this->productStock->setCreate($request)
            );

            if (
                $request->has('product_attribute_options')
                && $this->productStock->product->productAttributeOptions()->exists()
                && count($request->product_attribute_options)
            ) {
                $this->productStock
                    ->productAttributeOptions()
                    ->sync($request->product_attribute_options);
            }
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
