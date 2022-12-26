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

            if (
                array_key_exists('product_attribute_options', $fields)
                && count($fields['product_attribute_options'])
            ) {
                app(SyncProductAttributeOptions::class)($this->product, $fields['product_attribute_options']);
            }

            app(SyncProductImages::class)($this->product, $fields['images']);
            app(SyncProductTags::class)($this->product, $fields['tags']);
            app(SyncProductOptions::class)($this->product);

            return $this->product;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
