<?php

namespace App\Actions\Product;

use App\Models\Product;

class SyncProductOptions
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(Product $product)
    {
        try {
            $options = 'category_' . $product->category()->pluck('name')->implode('');

            $options .= '|tag_' . $product->tags()->pluck('name')->implode('|tag_');

            if ($product->productAttributeOptions()->count()) {
                $options .= '|' . $product->productAttributeOptions()
                    ->select(['name', 'product_attribute_id'])
                    ->with('productAttribute:id,name')
                    ->get()
                    ->map(function ($productAttributeOption) {
                        return $productAttributeOption->productAttribute->name . '_' . $productAttributeOption->name;
                    })->implode('|');
            }

            return $product->update([
                'options' => $options
            ]);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
