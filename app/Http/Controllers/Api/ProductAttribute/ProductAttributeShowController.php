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
     * Mostrar atributo de producto
     * 
     * Muestra la información de un atributo de producto indicado por el id.
     * 
     * @group Atributos de productos
     * @authenticated
     * @apiResource App\Http\Resources\ProductAttributeResource
     * @apiResourceModel App\Models\ProductAttribute with=status
     * 
     * @urlParam id int required Id del atributo de producto.
     */
    public function __invoke(Request $request, ProductAttribute $productAttribute)
    {
        $includes = explode(',', $request->get('include', ''));

        return $this->showOne(
            $productAttribute->loadEagerLoadIncludes($includes)
        );
    }
}
