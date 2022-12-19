<?php

namespace App\Actions\ProductStock;

use App\Models\Product;
use App\Models\ProductStock;

class StoreProductStock
{
    private $productStock;

    public function __construct(ProductStock $productStock)
    {
        $this->productStock = $productStock;
    }

    /**
     * Handle the incoming action.
     */
    public function __invoke(Product $product, array $fields)
    {
        try {
            $this->productStock = $product
                ->productStocks()
                ->create($fields);

            $this->productStock
                ->productAttributeOptions()
                ->sync($fields['product_attribute_options']);

            if (array_key_exists('images', $fields) && count($fields['images'])) {
                app(UpsertProductStockImages::class)($this->productStock, $fields['images']);
            }

            return $this->productStock;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
