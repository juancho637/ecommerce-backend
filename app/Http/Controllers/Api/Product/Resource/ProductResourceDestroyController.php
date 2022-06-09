<?php

namespace App\Http\Controllers\Api\Product\Resource;

use App\Models\Product;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;

class ProductResourceDestroyController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('can:delete,product')->only('__invoke');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Product $product, Resource $resource)
    {
        if (!$product->photos->contains($resource)) {
            throw new \Exception(__("The file does not belong to the product"));
        }

        DB::beginTransaction();
        try {
            $resource->delete();
            $resource->deleteFile($resource->path);
            DB::commit();

            return $this->showOne($resource);
        } catch (\Exception $exception) {
            DB::rollback();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
