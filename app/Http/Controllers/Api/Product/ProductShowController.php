<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\ApiController;

class ProductShowController extends ApiController
{
    public function __construct()
    {
    }

    /**
     * Mostrar producto
     * 
     * Muestra la información de un producto por el id.
     * 
     * @group Productos
     * @apiResource App\Http\Resources\ProductResource
     * @apiResourceModel App\Models\Product with=status,category,tags,productAttributeOptions,photos
     * 
     * @urlParam id int required Id del producto.
     */
    public function __invoke(Request $request, Product $product)
    {
        $includes = explode(',', $request->get('include', ''));

        if ($product->validByRole()) {
            return $this->showOne(
                $product->loadEagerLoadIncludes($includes)
            );
        }

        return $this->errorResponse(__('Not found'), Response::HTTP_NOT_FOUND);
    }
}
