<?php

namespace App\Actions\Product;

use App\Models\Product;

class SyncProductTags
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(Product $product, array $tags)
    {
        try {
            return $product->tags()->sync($tags);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
