<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Actions\ProductStock\StoreProductStock;

class StoreProductStockStep
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(array $fields, Product $product)
    {
        try {
            $product->update($fields);

            foreach ($fields['stocks'] as $objectProductStock) {
                app(StoreProductStock::class)(
                    $objectProductStock,
                    $product->id,
                    $product->type,
                );
            }

            return $product->productStocks();
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
