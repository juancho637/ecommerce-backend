<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Models\Resource;
use App\Actions\Resource\DeleteFile;

class SyncProductImages
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(Product $product, array $images)
    {
        try {
            $imagesSaved = [];
            // $path = strtolower(class_basename($product));

            foreach ($images as $image) {
                $imageUnsync = Resource::find($image['id']);
                $oldImage = $product->images()
                    ->where('options->location', $image['location'])
                    ->first();
                $options['location'] = $image['location'];

                if ($oldImage) {
                    app(DeleteFile::class)($oldImage['path']);
                }

                $imageUnsync->obtainable_type = Product::class;
                $imageUnsync->obtainable_id = $product->id;
                $imageUnsync->type_resource = Product::PRODUCT_IMAGE;
                $imageUnsync->options = $options;
                $imageUnsync->save();

                $imagesSaved[] = $imageUnsync;
            }

            return $imagesSaved;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
