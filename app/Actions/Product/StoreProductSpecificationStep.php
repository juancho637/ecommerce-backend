<?php

namespace App\Actions\Product;

use App\Actions\ProductSpecification\StoreProductSpecification;
use App\Models\Product;
use App\Models\Status;

class StoreProductSpecificationStep
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Handle the incoming action.
     */
    public function __invoke(array $fields, Product $product)
    {
        try {
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
