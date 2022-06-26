<?php

namespace App\Http\Controllers\Api\ProductAttribute;

use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProductAttribute\StoreProductAttributeRequest;

class ProductAttributeStoreController extends ApiController
{
    private $productAttribute;

    public function __construct(ProductAttribute $productAttribute)
    {
        $this->productAttribute = $productAttribute;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . ProductAttribute::class)->only('__invoke');
    }

    /**
     * Guardar atributo de producto
     * 
     * Guarda una atributo de producto en la aplicación.
     * 
     * @group Atributos de productos
     * @authenticated
     * @apiResource App\Http\Resources\ProductAttributeResource
     * @apiResourceModel App\Models\ProductAttribute with=status
     */
    public function __invoke(StoreProductAttributeRequest $request)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->productAttribute = $this->productAttribute->create(
                $this->productAttribute->setCreate($request)
            );
            DB::commit();

            return $this->showOne(
                $this->productAttribute
                    ->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
