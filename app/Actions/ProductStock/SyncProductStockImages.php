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
            $imagesSaved = [];
            // $path = strtolower(class_basename($product));

            foreach ($images as $image) {
                $imageToSync = Resource::find($image);
                $oldImage = $productStock->images->first();

                if ($oldImage) {
                    app(DeleteFile::class)($oldImage['path']);
                }

                $imageToSync->obtainable_type = ProductStock::class;
                $imageToSync->obtainable_id = $productStock->id;
                $imageToSync->type_resource = ProductStock::PRODUCT_STOCK_IMAGE;
                $imageToSync->save();

                $imagesSaved[] = $imageToSync;
            }

            return $imagesSaved;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
