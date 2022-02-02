<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;

class ProductDestroyController extends ApiController
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;

        $this->middleware('auth:sanctum');

        $this->middleware('can:delete,product')->only('__invoke');
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

        DB::beginTransaction();
        try {
            $this->product = $product->setDelete();
            $this->product->save();
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
