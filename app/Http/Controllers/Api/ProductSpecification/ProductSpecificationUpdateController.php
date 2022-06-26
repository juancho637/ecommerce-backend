<?php

namespace App\Http\Controllers\Api\ProductSpecification;

use Illuminate\Support\Facades\DB;
use App\Models\ProductSpecification;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProductSpecification\UpdateProductSpecificationRequest;

class ProductSpecificationUpdateController extends ApiController
{
    private $productSpecification;

    public function __construct(ProductSpecification $productSpecification)
    {
        $this->productSpecification = $productSpecification;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update,productSpecification')->only('__invoke');
    }

    /**
     * Actualizar especificación del producto
     * 
     * Actualiza la especificación del producto indicado por el id.
     * 
     * @group Especificaciones de productos
     * @authenticated
     * @apiResource App\Http\Resources\ProductSpecificationResource
     * @apiResourceModel App\Models\ProductSpecification with=status,product
     * 
     * @urlParam productSpecification int required Id de la especificación del producto.
     */
    public function __invoke(
        UpdateProductSpecificationRequest $request,
        ProductSpecification $productSpecification
    ) {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->productSpecification = $productSpecification->setUpdate($request);
            $this->productSpecification->save();
            DB::commit();

            return $this->showOne(
                $this->productSpecification
                    ->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
