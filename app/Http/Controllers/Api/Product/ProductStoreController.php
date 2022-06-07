<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Tag;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\ProductAttributeOption;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Product\StoreProductRequest;

class ProductStoreController extends ApiController
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;

        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . Product::class)->only('__invoke');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreProductRequest $request)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $options = "category:" . Category::find($request->category_id, ['name'])->name;
            $options .= "|tag:" . Tag::whereIn("id", $request->tags)->pluck('name')->implode('|tag:');

            $request['options'] = $options;
            $this->product = $this->product->create(
                $this->product->setCreate($request)
            );

            $this->product->tags()->sync($request->tags);

            if (
                $request->has('product_attribute_options')
                && count($request->product_attribute_options)
            ) {
                $attributes = "|" . ProductAttributeOption::select(['name', 'product_attribute_id'])
                    ->whereIn("id", $request->product_attribute_options)
                    ->with('productAttribute:id,name')
                    ->get()
                    ->map(function ($item) {
                        return $item->productAttribute->name . ':' . $item->name;
                    })->implode('|');

                $this->product
                    ->productAttributeOptions()
                    ->sync($request->product_attribute_options);

                $this->product->options .= $attributes;
                $this->product->save();
            }
            DB::commit();

            return $this->showOne(
                $this->product->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
