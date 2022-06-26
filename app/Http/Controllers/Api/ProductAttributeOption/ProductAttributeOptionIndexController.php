<?php

namespace App\Http\Controllers\Api\ProductAttributeOption;

use Illuminate\Http\Request;
use App\Models\ProductAttributeOption;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class ProductAttributeOptionIndexController extends ApiController
{
    private $productAttributeOption;

    public function __construct(ProductAttributeOption $productAttributeOption)
    {
        $this->productAttributeOption = $productAttributeOption;

        $this->middleware('auth:sanctum');

        $this->middleware('can:view-any,' . ProductAttributeOption::class)->only('__invoke');
    }

    /**
     * Listar opciones de atributos
     * 
     * Lista los opciones de atributos de la aplicación.
     * 
     * @group Opciones de atributos
     * @authenticated
     * @apiResourceCollection App\Http\Resources\ProductAttributeOptionResource
     * @apiResourceModel App\Models\ProductAttributeOption with=status,productAttribute
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $productAttributeOptions = $this->productAttributeOption->query();
        $productAttributeOptions = $this->eagerLoadIncludes($productAttributeOptions, $includes)->get();

        return $this->showAll($productAttributeOptions);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('product_attribute', $includes)) {
            $query->with('productAttribute');
        }

        return $query;
    }
}
