<?php

namespace App\Http\Controllers\Api\Product\ProductStock;

use App\Models\Product;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Product\ProductStock\StoreProductProductStockRequest;

class ProductProductStockStoreController extends ApiController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreProductProductStockRequest $request, Product $product)
    {
        $includes = explode(',', $request->get('include', ''));
        //
    }
}
