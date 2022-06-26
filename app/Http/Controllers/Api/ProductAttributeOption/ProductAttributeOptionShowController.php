<?php

namespace App\Http\Controllers\Api\ProductAttributeOption;

use Illuminate\Http\Request;
use App\Models\ProductAttributeOption;
use App\Http\Controllers\Api\ApiController;

class ProductAttributeOptionShowController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('can:view,productAttributeOption')->only('__invoke');
    }

    /**
     * Mostrar opción de atributo
     * 
     * Muestra la información de una opción de atributo indicado por el id.
     * 
     * @group Opciones de atributos
     * @authenticated
     * @apiResource App\Http\Resources\ProductAttributeOptionResource
     * @apiResourceModel App\Models\ProductAttributeOption with=status,productAttribute
     * 
     * @urlParam productAttributeOption int required Id de la opción de atributo.
     */
    public function __invoke(Request $request, ProductAttributeOption $productAttributeOption)
    {
        $includes = explode(',', $request->get('include', ''));

        return $this->showOne(
            $productAttributeOption->loadEagerLoadIncludes($includes)
        );
    }
}
