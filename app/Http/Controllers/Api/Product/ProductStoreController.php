<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
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
            $this->product = $this->product->create(
                $this->product->setCreate($request)
            );

            $this->product->tags()->sync($request->tags);

            if (
                $request->has('product_attribute_options')
                && count($request->product_attribute_options)
            ) {
                $this->product
                    ->productAttributeOptions()
                    ->sync($request->product_attribute_options);
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
