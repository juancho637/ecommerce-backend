<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Models\Resource;
use App\Actions\Resource\DeleteResource;

class SyncCategoryImages
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(Category $category, int $imageId)
    {
        try {
            // $path = strtolower(class_basename($category));
            $imageToSync = Resource::find($imageId);
            $oldImage = $category->image;

            if ($oldImage) {
                app(DeleteResource::class)($oldImage);
            }

            $imageToSync->obtainable_type = Category::class;
            $imageToSync->obtainable_id = $category->id;
            $imageToSync->type_resource = Category::CATEGORY_IMAGE;
            $imageToSync->save();

            return $imageToSync;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
