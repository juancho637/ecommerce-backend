<?php

namespace App\Http\Controllers\Api\ProductAttributeOption;

use Illuminate\Support\Facades\DB;
use App\Models\ProductAttributeOption;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProductAttributeOption\StoreProductAttributeOptionRequest;

class ProductAttributeOptionStoreController extends ApiController
{
    private $productAttributeOption;

    public function __construct(ProductAttributeOption $productAttributeOption)
    {
        $this->productAttributeOption = $productAttributeOption;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . ProductAttributeOption::class)->only('__invoke');
    }

    /**
     * Guardar opción de atributo
     * 
     * Guarda una opción de atributo en la aplicación.
     * 
     * @group Opciones de atributos
     * @authenticated
     * @apiResource App\Http\Resources\ProductAttributeOptionResource
     * @apiResourceModel App\Models\ProductAttributeOption with=status,productAttribute
     */
    public function __invoke(StoreProductAttributeOptionRequest $request)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->productAttributeOption = $this->productAttributeOption->create(
                $this->productAttributeOption->setCreate($request)
            );
            DB::commit();

            return $this->showOne(
                $this->productAttributeOption
                    ->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
