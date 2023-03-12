<?php

namespace App\Actions\ProductStock;

use App\Models\Resource;
use App\Models\ProductStock;
use App\Actions\Resource\DeleteFile;

class SyncProductStockImages
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(ProductStock $productStock, array $images)
    {
        try {
            if (array_key_exists('detach', $images) && count($images['detach'])) {
                foreach ($images['detach'] as $image) {
                    $resource = Resource::find($image);

                    app(DeleteResource::class)($resource);
                }
            }

            if (array_key_exists('attach', $images) && count($images['attach'])) {
                foreach ($images['attach'] as $image) {
                    $resource = Resource::find($image);

                    $resource->obtainable_type = ProductStock::class;
                    $resource->obtainable_id = $productStock->id;
                    $resource->type_resource = ProductStock::PRODUCT_STOCK_IMAGE;

                    $resource->save();
                }
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
