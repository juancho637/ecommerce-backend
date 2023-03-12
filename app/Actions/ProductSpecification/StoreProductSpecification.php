<?php

namespace App\Actions\ProductSpecification;

use App\Models\ProductSpecification;

class StoreProductSpecification
{
    private $productSpecification;

    public function __construct(ProductSpecification $productSpecification)
    {
        $this->productSpecification = $productSpecification;
    }

    /**
     * Handle the incoming action.
     */
    public function __invoke(array $fields, int $productId)
    {
        try {
            $productSpecificationFields = $this->productSpecification
                ->setCreate(
                    $fields,
                    $productId,
                );

            $this->productSpecification = $this->productSpecification
                ->create($productSpecificationFields);

            return $this->productSpecification;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
