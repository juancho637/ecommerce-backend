<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Role;
use App\Models\Status;
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
        $search = $request->get('search', '');

        $products = Product::search($search)->query(function ($query) use ($includes) {
            $query->byRole();
            $this->eagerLoadIncludes($query, $includes);
        })->get();

        return $this->showAll($products);
    }

    protected function eagerLoadIncludes(Builder $query, array $includes)
    {
        $user = auth('sanctum')->user();

        if (in_array('status', $includes)) {
            $query->with('status');
        }

        if (in_array('category', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $query->with(['category']);
            } else {
                $query->with(['category' => function ($query) {
                    $query->whereHas('status', function ($query) {
                        $query->where('name', Status::ENABLED);
                    });
                }]);
            }
        }

        if (in_array('tags', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $query->with(['tags']);
            } else {
                $query->with(['tags' => function ($query) {
                    $query->whereHas('status', function ($query) {
                        $query->where('name', Status::ENABLED);
                    });
                }]);
            }
        }

        if (in_array('product_attribute_options', $includes)) {
            if ($user && $user->hasRole(Role::ADMIN)) {
                $query->with(['productAttributeOptions.productAttribute']);
            } else {
                $query->with([
                    'productAttributeOptions.productAttribute' => function ($query) {
                        $query->whereHas('status', function ($query) {
                            $query->where('name', Status::ENABLED);
                        });
                    }
                ]);
            }
        }

        return $query;
    }
}
