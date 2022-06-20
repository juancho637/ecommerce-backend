<?php

namespace App\Actions\Product;

use App\Models\Product;

class SyncProductAttributeOptions
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(Product $product, array $productAttributeOptions)
    {
        try {
            return $product
                ->productAttributeOptions()
                ->sync($productAttributeOptions);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
