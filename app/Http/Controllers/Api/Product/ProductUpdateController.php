<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Product\UpdateProductRequest;

class ProductUpdateController extends ApiController
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;

        $this->middleware('auth:sanctum');

        $this->middleware('can:update,product')->only('__invoke');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $this->product = $product->setUpdate($request);
            $this->product->save();

            if ($request->has('tags') && count($request->tags)) {
                $this->product->tags()->sync($request->tags);
            }
            DB::commit();

            return $this->showOne($this->product);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
