<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Actions\Resource\StoreResource;
use App\Actions\Resource\UpdateResource;

class UpsertProductImages
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(Product $product, array $images)
    {
        $imagesSaved = [];
        $path = strtolower(class_basename($product));

        foreach ($images as $image) {
            $oldImage = $product->images()
                ->where('options->location', $image['location'])
                ->first();
            $options['location'] = $image['location'];

            if ($oldImage) {
                $imagesSaved[] = app(UpdateResource::class)(
                    resource: $oldImage,
                    file: $image['file'],
                    type: Product::class,
                    typeId: $product->id,
                    typeResource: Product::PRODUCT_IMAGE,
                    path: $path,
                    isImage: true,
                    disk: Product::DISK_PRODUCT_IMAGE,
                    options: $options
                );
            } else {
                $imagesSaved[] = app(StoreResource::class)(
                    file: $image['file'],
                    type: Product::class,
                    typeId: $product->id,
                    typeResource: Product::PRODUCT_IMAGE,
                    path: $path,
                    isImage: true,
                    disk: Product::DISK_PRODUCT_IMAGE,
                    options: $options
                );
            }
        }

        return $imagesSaved;
    }
}
