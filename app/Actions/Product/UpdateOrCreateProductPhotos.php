<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Actions\Resource\StoreResource;
use App\Actions\Resource\UpdateResource;

class UpdateOrCreateProductPhotos
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(Product $product, array $photos)
    {
        $photosSaved = [];
        $path = strtolower(class_basename($product));

        foreach ($photos as $photo) {
            $oldPhoto = $product->photos()->where($photo['location'])->first();

            if ($oldPhoto) {
                $photosSaved[] = app(UpdateResource::class)(
                    resource: $oldPhoto,
                    file: $photo['file'],
                    type: Product::class,
                    id: $product->id,
                    typeResource: Product::PRODUCT_PHOTO,
                    path: $path,
                    isImage: true,
                    disk: Product::DISK_PRODUCT_PHOTO,
                    options: $photo['location']
                );
            } else {
                $photosSaved[] = app(StoreResource::class)(
                    file: $photo['file'],
                    type: Product::class,
                    id: $product->id,
                    typeResource: Product::PRODUCT_PHOTO,
                    path: $path,
                    isImage: true,
                    disk: Product::DISK_PRODUCT_PHOTO,
                    options: $photo['location']
                );
            }
        }

        return $photosSaved;
    }
}
