<?php

namespace App\Actions\ProductSpecification;

use App\Models\ProductSpecification;

class UpdateProductSpecification
{
    private $productSpecification;

    public function __construct(ProductSpecification $productSpecification)
    {
        $this->productSpecification = $productSpecification;
    }

    /**
     * Handle the incoming action.
     */
    public function __invoke(array $fields, ProductSpecification $productSpecification)
    {
        try {
            $productSpecification->update($fields);

            return $productSpecification;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
