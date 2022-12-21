<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Models\Status;

class FinishProduct
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Handle the incoming action.
     */
    public function __invoke(Product $product, array $fields)
    {
        try {
            $this->product = $product;
            $enabledStatus = Status::enabled()->value('id');
            $specifications = $fields['specifications'];

            foreach ($specifications as $key => $specification) {
                $specifications[$key]['status_id'] = $enabledStatus;
            }

            $this->product->productSpecifications()->createMany($specifications);

            return $this->product;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
