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
                    $resource = Resource::find($image);

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
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
