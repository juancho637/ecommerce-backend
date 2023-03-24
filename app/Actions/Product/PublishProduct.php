<?php

namespace App\Actions\Product;

use App\Models\Status;
use App\Models\Product;
use Illuminate\Http\Response;

class PublishProduct
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(array $fields, Product $product)
    {
        try {
            if ($product->status_id === Status::disabled()->value('id')) {
                throw new \Exception(__('The product is disabled'), Response::HTTP_BAD_REQUEST);
            }

            if (
                $product->is_variable
                && $product->status_id === Status::productGeneralStep()->value('id')
            ) {
                throw new \Exception(__('The product cannot be published'), Response::HTTP_BAD_REQUEST);
            }

            $product->update($fields);

            return $product;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
