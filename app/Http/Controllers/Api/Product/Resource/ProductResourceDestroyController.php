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
     * Eliminar foto de producto
     * 
     * Elimina una foto de un producto por el id.
     * 
     * @group Productos
     * @authenticated
     * @apiResource App\Http\Resources\ResourceResource
     * @apiResourceModel App\Models\Resource
     * 
     * @urlParam product_id int required Id del producto.
     * @urlParam resource int required Id de la foto del producto.
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
