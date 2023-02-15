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
            // return $product
            //     ->productAttributeOptions()
            //     ->sync($productAttributeOptions);

            if (
                array_key_exists('detach', $productAttributeOptions)
                && count($productAttributeOptions['detach'])
            ) {
                $product->productAttributeOptions()
                    ->detach($productAttributeOptions['detach']);
            }

            if (
                array_key_exists('attach', $productAttributeOptions)
                && count($productAttributeOptions['attach'])
            ) {
                $product->productAttributeOptions()
                    ->attach($productAttributeOptions['attach']);
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
