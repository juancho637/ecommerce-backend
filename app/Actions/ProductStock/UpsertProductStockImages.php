<?php

namespace App\Actions\ProductStock;

use App\Models\ProductStock;
use App\Actions\Resource\StoreResource;
use App\Actions\Resource\UpdateResource;

class UpsertProductStockImages
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(ProductStock $productStock, array $images)
    {
        $imagesSaved = [];
        $path = strtolower(class_basename($productStock));

        foreach ($images as $image) {
            // $oldImage = $productStock->images()->first();
            $oldImage = false;

            if ($oldImage) {
                $imagesSaved[] = app(UpdateResource::class)(
                    resource: $oldImage,
                    file: $image,
                    type: ProductStock::class,
                    typeId: $productStock->id,
                    typeResource: ProductStock::PRODUCT_STOCK_IMAGE,
                    path: $path,
                    isImage: true,
                    disk: ProductStock::DISK_PRODUCT_STOCK_IMAGE,
                    // options: $options
                );
            } else {
                $imagesSaved[] = app(StoreResource::class)(
                    file: $image,
                    type: ProductStock::class,
                    typeId: $productStock->id,
                    typeResource: ProductStock::PRODUCT_STOCK_IMAGE,
                    path: $path,
                    isImage: true,
                    disk: ProductStock::DISK_PRODUCT_STOCK_IMAGE,
                    // options: $options
                );
            }
        }

        return $imagesSaved;
    }
}
