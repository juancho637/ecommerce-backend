<?php

namespace App\Http\Controllers\Api\ProductSpecification;

use Illuminate\Support\Facades\DB;
use App\Models\ProductSpecification;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProductSpecification\StoreProductSpecificationRequest;

class ProductSpecificationStoreController extends ApiController
{
    private $productSpecification;

    public function __construct(ProductSpecification $productSpecification)
    {
        $this->productSpecification = $productSpecification;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . ProductSpecification::class)->only('__invoke');
    }

    /**
     * Guardar especificación del producto
     * 
     * Guarda una especificación del producto en la aplicación.
     * 
     * @group Especificaciones de productos
     * @authenticated
     * @apiResource App\Http\Resources\ProductSpecificationResource
     * @apiResourceModel App\Models\ProductSpecification with=status,product
     */
    public function __invoke(StoreProductSpecificationRequest $request)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->productSpecification = $this->productSpecification->create(
                $this->productSpecification->setCreate($request)
            );
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
