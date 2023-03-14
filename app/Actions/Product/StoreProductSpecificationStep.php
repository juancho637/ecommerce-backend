<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Actions\ProductSpecification\StoreProductSpecification;

class StoreProductSpecificationStep
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(array $fields, Product $product)
    {
        try {
            $product->update($fields);

            foreach ($fields['specifications'] as $objectProductSpecification) {
                app(StoreProductSpecification::class)(
                    $objectProductSpecification,
                    $product->id,
                );
            }

            return $product->productSpecifications();
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
