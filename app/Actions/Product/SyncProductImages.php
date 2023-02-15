<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Models\Resource;
use App\Actions\Resource\DeleteResource;

class SyncProductImages
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(Product $product, array $images)
    {
        try {
            if (array_key_exists('detach', $images) && count($images['detach'])) {
                foreach ($images['detach'] as $image) {
                    $resource = Resource::find($image['id']);

                    app(DeleteResource::class)($resource);
                }
            }

            if (array_key_exists('attach', $images) && count($images['attach'])) {
                foreach ($images['attach'] as $image) {
                    $resource = Resource::find($image['id']);
                    $options['location'] = $image['location'];

                    $resource->obtainable_type = Product::class;
                    $resource->obtainable_id = $product->id;
                    $resource->type_resource = Product::PRODUCT_IMAGE;
                    $resource->options = $options;

                    $resource->save();
                }
            }

            // $imagesSaved = [];
            // // $path = strtolower(class_basename($product));

            // foreach ($images as $image) {
            //     $imageUnsync = Resource::find($image['id']);
            //     $oldImage = $product->images()
            //         ->where('options->location', $image['location'])
            //         ->first();
            //     $options['location'] = $image['location'];

            //     if ($oldImage) {
            //         app(DeleteResource::class)($oldImage['path']);
            //     }

            //     $imageUnsync->obtainable_type = Product::class;
            //     $imageUnsync->obtainable_id = $product->id;
            //     $imageUnsync->type_resource = Product::PRODUCT_IMAGE;
            //     $imageUnsync->options = $options;
            //     $imageUnsync->save();

            //     $imagesSaved[] = $imageUnsync;
            // }

            // return $imagesSaved;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
