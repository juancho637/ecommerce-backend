<?php

namespace App\Actions\Category;

use App\Models\Category;

class StoreCategory
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Handle the incoming action.
     */
    public function __invoke(array $fields)
    {
        try {
            $this->category = $this->category->create($fields);
            app(SyncCategoryImages::class)($this->category, $fields['image']);

            return $this->category;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
