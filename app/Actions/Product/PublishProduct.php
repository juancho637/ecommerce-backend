<?php

namespace App\Actions\Product;

use App\Models\Status;
use App\Models\Product;

class PublishProduct
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
            if ($product->status_id === Status::disabled()->value('id')) {
                throw new \Exception(__('The product is disabled'));
            }

            if (
                $product->is_variable
                && $product->status_id === Status::productGeneralStep()->value('id')
            ) {
                throw new \Exception(__('The product cannot be published'));
            }

            $this->product = $product->update($fields);

            return $this->product;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
