<?php

namespace App\Actions\ProductStock;

use App\Models\Product;
use App\Models\ProductStock;

class UpdateProductStock
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(array $fields, Product $product, ProductStock $productStock)
    {
        try {
            $oldProduct = $product;
            $product->update($fields);

            if ($oldProduct->is_variable === false && $product->is_variable === true) {
                // TODO: Desabled product stock
            }

            if ($oldProduct->is_variable === true && $product->is_variable === false) {
                // TODO: Desabled product stocks
            }

            if (array_key_exists('images', $fields) && count($fields['images'])) {
                app(SyncProductImages::class)($product, $fields['images']);
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

            return $productStock;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
