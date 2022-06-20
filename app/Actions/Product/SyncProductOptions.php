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
            // dd($product->category()->pluck('name')->implode(''));
            $options = 'category:' . $product->category()->pluck('name')->implode('');

            $options .= '|tag:' . $product->tags()->pluck('name')->implode('|tag:');

            if ($product->productAttributeOptions()->count()) {
                $options .= '|' . $product->productAttributeOptions()
                    ->select(['name', 'product_attribute_id'])
                    ->with('productAttribute:id,name')
                    ->get()
                    ->map(function ($productAttributeOption) {
                        return $productAttributeOption->productAttribute->name . ':' . $productAttributeOption->name;
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
