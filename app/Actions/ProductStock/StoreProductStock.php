<?php

namespace App\Actions\ProductStock;

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
    public function __invoke(array $fields, int $productId, string $productType)
    {
        try {
            $productStockFields = $this->productStock->setCreate(
                $fields,
                $productId,
                $productType
            );

            $this->productStock = $this->productStock->create($productStockFields);

            $this->productStock
                ->productAttributeOptions()
                ->sync($productStockFields['product_attribute_options']);

            if (array_key_exists('images', $fields) && count($fields['images'])) {
                app(SyncProductStockImages::class)(
                    $this->productStock,
                    $productStockFields['images']
                );
            }

            return $this->productStock;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
