<?php

namespace App\Actions\Product;

use App\Models\Product;

class StoreProduct
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Handle the incoming action.
     */
    public function __invoke(array $fields)
    {
        try {
            $this->product = $this->product->create($fields);

            if (array_key_exists('images', $fields) && count($fields['images'])) {
                app(UpsertProductImages::class)($this->product, $fields['images']);
            }

            if (array_key_exists('tags', $fields) && count($fields['tags'])) {
                app(SyncProductTags::class)($this->product, $fields['tags']);
            }

            if (
                array_key_exists('product_attribute_options', $fields)
                && count($fields['product_attribute_options'])
            ) {
                app(SyncProductAttributeOptions::class)($this->product, $fields['product_attribute_options']);
            }

            app(SyncProductOptions::class)($this->product);

            return $this->product;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
