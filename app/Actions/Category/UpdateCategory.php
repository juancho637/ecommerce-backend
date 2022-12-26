<?php

namespace App\Actions\Category;

use App\Models\Category;

class UpdateCategory
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(Category $category, array $fields)
    {
        try {
            $category->update($fields);

            if (array_key_exists('image', $fields) && count($fields['image'])) {
                app(SyncCategoryImages::class)($category, $fields['image']);
            }

            return $category;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
