<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\ApiController;

class ProductShowController extends ApiController
{
    public function __construct()
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Product $product)
    {
        if ($product->validByRole()) {
            return $this->showOne($product);
        }

        return $this->errorResponse(__('Not found'), Response::HTTP_NOT_FOUND);
    }
}
