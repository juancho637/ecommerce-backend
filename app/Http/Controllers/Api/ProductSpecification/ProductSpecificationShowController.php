<?php

namespace App\Http\Controllers\Api\ProductSpecification;

use App\Models\ProductSpecification;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class ProductSpecificationShowController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('can:view,productSpecification')->only('__invoke');
    }

    /**
     * Mostrar especificación del producto
     * 
     * Muestra la información de una especificación del producto indicado por el id.
     * 
     * @group Especificaciones de productos
     * @authenticated
     * @apiResource App\Http\Resources\ProductSpecificationResource
     * @apiResourceModel App\Models\ProductSpecification with=status,product
     * 
     * @urlParam productSpecification int required Id de la especificación del producto.
     */
    public function __invoke(Request $request, ProductSpecification $productSpecification)
    {
        $includes = explode(',', $request->get('include', ''));

        return $this->showOne(
            $productSpecification->loadEagerLoadIncludes($includes)
        );
    }
}
