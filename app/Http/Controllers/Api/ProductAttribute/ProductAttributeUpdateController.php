<?php

namespace App\Http\Controllers\Api\ProductAttribute;

use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProductAttribute\UpdateProductAttributeRequest;

class ProductAttributeUpdateController extends ApiController
{
    private $productAttribute;

    public function __construct(ProductAttribute $productAttribute)
    {
        $this->productAttribute = $productAttribute;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update,productAttribute')->only('__invoke');
    }

    /**
     * Actualizar atributo de producto
     * 
     * Actualiza el atributo de producto indicado por el id.
     * 
     * @group Atributos de productos
     * @authenticated
     * @apiResource App\Http\Resources\ProductAttributeResource
     * @apiResourceModel App\Models\ProductAttribute with=status
     * 
     * @urlParam id int required Id del atributo de producto.
     */
    public function __invoke(
        UpdateProductAttributeRequest $request,
        ProductAttribute $productAttribute
    ) {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->productAttribute = $productAttribute->setUpdate($request);
            $this->productAttribute->save();
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
