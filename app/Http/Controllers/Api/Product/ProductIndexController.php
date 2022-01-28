<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Api\ApiController;

class ProductIndexController extends ApiController
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $includes = explode(',', $request->get('include', ''));

        $products = $this->product->query()->byRole();
        $products = $this->eagerLoadIncludes($products, $includes)->get();

        return $this->showAll($products);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('category', $includes)) {
            $query->with('category');
        }

        if (in_array('tags', $includes)) {
            $query->with('tags');
        }

        return $query;
    }
}
