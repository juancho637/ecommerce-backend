<?php

namespace App\Http\Controllers\Api\ProductAttribute;

use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Http\Controllers\Api\ApiController;

class ProductAttributeShowController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('can:view,productAttribute')->only('__invoke');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ProductAttribute $productAttribute)
    {
        $includes = explode(',', $request->get('include', ''));

        return $this->showOne(
            $productAttribute->loadEagerLoadIncludes($includes)
        );
    }
}
