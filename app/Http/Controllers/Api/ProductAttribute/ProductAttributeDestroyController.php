<?php

namespace App\Http\Controllers\Api\ProductAttribute;

use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;

class ProductAttributeDestroyController extends ApiController
{
    private $productAttribute;

    public function __construct(ProductAttribute $productAttribute)
    {
        $this->productAttribute = $productAttribute;

        $this->middleware('auth:sanctum');

        $this->middleware('can:delete,productAttribute')->only('__invoke');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ProductAttribute $productAttribute)
    {
        $includes = explode(',', $request->get('include', ''));

        DB::beginTransaction();
        try {
            $this->productAttribute = $productAttribute->setDelete();
            $this->productAttribute->save();
            DB::commit();

            return $this->showOne(
                $this->productAttribute->loadEagerLoadIncludes($includes)
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
