<?php

namespace App\Http\Controllers\Api\Product\ProductStock;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class ProductProductStockIndexController extends ApiController
{
    private $product;
    private $productStock;

    public function __construct(Product $product, ProductStock $productStock)
    {
        $this->product = $product;
        $this->productStock = $productStock;

        $this->middleware('auth:sanctum');

        $this->middleware('can:view-any,' . ProductStock::class)->only('__invoke');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Product $product)
    {
        $includes = explode(',', $request->get('include', ''));

        $productStocks = $product->productStocks->query();
        $productStocks = $this->eagerLoadIncludes($productStocks, $includes)->get();

        return $this->showAll($productStocks);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $this->with(['status']);
        }

        if (in_array('product', $includes)) {
            $this->with(['product']);
        }

        if (in_array('product_attribute_options', $includes)) {
            $this->with(['productAttributeOptions.productAttribute']);
        }

        return $query;
    }
}
