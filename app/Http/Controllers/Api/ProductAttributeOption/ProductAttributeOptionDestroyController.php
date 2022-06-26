<?php

namespace App\Http\Controllers\Api\ProductAttributeOption;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductAttributeOption;
use App\Http\Controllers\Api\ApiController;

class ProductAttributeOptionDestroyController extends ApiController
{
    private $productAttributeOption;

    public function __construct(ProductAttributeOption $productAttributeOption)
    {
        $this->productAttributeOption = $productAttributeOption;

        $this->middleware('auth:sanctum');

        $this->middleware('can:delete,productAttributeOption')->only('__invoke');
    }

    /**
     * Eliminar opción de atributo
     * 
     * Elimina una opción de atributo indicado por el id.
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

        DB::beginTransaction();
        try {
            $this->productAttributeOption = $productAttributeOption->setDelete();
            $this->productAttributeOption->save();
            DB::commit();

            return $this->showOne(
                $this->productAttributeOption->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
