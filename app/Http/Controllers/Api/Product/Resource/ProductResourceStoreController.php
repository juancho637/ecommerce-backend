<?php

namespace App\Http\Controllers\Api\Product\Resource;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Product\Resource\StoreProductResourceRequest;

class ProductResourceStoreController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('can:create,' . Product::class)->only('__invoke');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreProductResourceRequest $request, Product $product)
    {
        if ($product->photos->count() >= Product::MAX_PHOTOS) {
            throw new \Exception(__("The product exceeds the maximum of photos"));
        }

        DB::beginTransaction();
        try {
            $photo = $product->savePhotos($request->photo);
            DB::commit();

            return $this->showOne($photo);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
