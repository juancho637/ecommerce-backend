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
            $this->productSpecification = $this->productSpecification
                ->create(
                    $this->productSpecification
                        ->setCreate(
                            $fields,
                            $productId,
                        )
                );

            return $this->productSpecification;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
