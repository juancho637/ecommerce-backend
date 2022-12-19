<?php

namespace App\Http\Controllers\Api\ProductStock;

use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

class ProductStockShowController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('can:view,productStock')->only('__invoke');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ProductStock $productStock)
    {
        $includes = explode(',', $request->get('include', ''));

        return $this->showOne($productStock->loadEagerLoadIncludes($includes));
    }
}
