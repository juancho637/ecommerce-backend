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
                $imageUnsync = Resource::find($image['id']);
                $oldImage = $productStock->images()
                    ->where('options->location', $image['location'])
                    ->first();
                $options['location'] = $image['location'];

                if ($oldImage) {
                    app(DeleteFile::class)($oldImage['path']);
                }

                $imageUnsync->obtainable_type = ProductStock::class;
                $imageUnsync->obtainable_id = $productStock->id;
                $imageUnsync->type_resource = ProductStock::PRODUCT_STOCK_IMAGE;
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
