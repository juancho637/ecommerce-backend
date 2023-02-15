<?php

namespace App\Actions\Product;

use App\Models\Product;

class SyncProductTags
{
    /**
     * Handle the incoming action.
     */
    public function __invoke(Product $product, array $tags)
    {
        try {
            // return $product->tags()->sync($tags);

            if (array_key_exists('detach', $tags) && count($tags['detach'])) {
                $product->tags()->detach($tags['detach']);
            }

            if (array_key_exists('attach', $tags) && count($tags['attach'])) {
                $product->tags()->attach($tags['attach']);
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
