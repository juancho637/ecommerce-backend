<?php

namespace App\Http\Controllers\Api\ProductAttribute;

use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class ProductAttributeIndexController extends ApiController
{
    private $productAttribute;

    public function __construct(ProductAttribute $productAttribute)
    {
        $this->productAttribute = $productAttribute;

        $this->middleware('auth:sanctum');

        $this->middleware('can:view-any,' . ProductAttribute::class)->only('__invoke');
    }

    /**
     * Listar atributos de productos
     * 
     * Lista los atributos de productos de la aplicación.
     * 
     * @group Atributos de productos
     * @authenticated
     * @apiResourceCollection App\Http\Resources\ProductAttributeResource
     * @apiResourceModel App\Models\ProductAttribute with=status
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $productAttributes = $this->productAttribute->query();
        $productAttributes = $this->eagerLoadIncludes($productAttributes, $includes)->get();

        return $this->showAll($productAttributes);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        return $query;
    }
}
