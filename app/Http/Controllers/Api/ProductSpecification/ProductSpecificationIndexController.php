<?php

namespace App\Http\Controllers\Api\ProductSpecification;

use Illuminate\Http\Request;
use App\Models\ProductSpecification;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class ProductSpecificationIndexController extends ApiController
{
    private $productSpecification;

    public function __construct(ProductSpecification $productSpecification)
    {
        $this->productSpecification = $productSpecification;

        $this->middleware('auth:sanctum');

        $this->middleware('can:view-any,' . ProductSpecification::class)->only('__invoke');
    }

    /**
     * Listar especificaciones de los productos
     * 
     * Lista las especificaciones de los productos de la aplicación.
     * 
     * @group Especificaciones de productos
     * @authenticated
     * @apiResourceCollection App\Http\Resources\ProductSpecificationResource
     * @apiResourceModel App\Models\ProductSpecification with=status,product
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $productSpecifications = $this->productSpecification->query();
        $productSpecifications = $this->eagerLoadIncludes($productSpecifications, $includes)->get();

        return $this->showAll($productSpecifications);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('product', $includes)) {
            $query->with('product');
        }

        return $query;
    }
}
