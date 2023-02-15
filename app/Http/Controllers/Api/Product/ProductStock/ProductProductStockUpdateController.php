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
