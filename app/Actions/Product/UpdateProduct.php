<?php

namespace App\Actions\Product;

use App\Models\Product;

class UpdateProduct
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(Product $product, array $fields)
    {
        try {
            $product->update($fields);

            if (array_key_exists('photos', $fields) && count($fields['photos'])) {
                app(UpdateOrCreateProductPhotos::class)($product, $fields['photos']);
            }

            if (array_key_exists('tags', $fields) && count($fields['tags'])) {
                app(SyncProductTags::class)($product, $fields['tags']);
            }

            if (
                array_key_exists('product_attribute_options', $fields)
                && count($fields['product_attribute_options'])
            ) {
                app(SyncProductAttributeOptions::class)($product, $fields['product_attribute_options']);
            }

            app(SyncProductOptions::class)($product);

            return $product;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
