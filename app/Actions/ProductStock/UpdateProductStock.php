<?php

namespace App\Actions\ProductStock;

use App\Models\ProductStock;

class UpdateProductStock
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(array $fields, ProductStock $productStock)
    {
        try {
            $productStock->update($fields);

            if (array_key_exists('images', $fields) && count($fields['images'])) {
                app(SyncProductStockImages::class)($productStock, $fields['images']);
            }

            return $productStock;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
