<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Database\Seeder;

class ProductStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::all()->each(function ($product) {
            if ($product->productAttributeOptions()->exists()) {
                $productAttributeOptions = $product->load('productAttributeOptions')
                    ->productAttributeOptions
                    ->groupBy('product_attribute_id')
                    ->pluck('*.id');

                $combination = collect($productAttributeOptions
                    ->shift())->crossJoin(...$productAttributeOptions);

                $combination->each(function ($combination) use ($product) {
                    ProductStock::factory()
                        ->product($product)
                        ->combination($combination)
                        ->create();
                });
            } else {
                ProductStock::factory()
                    ->product($product)
                    ->create();
            }
        });
    }
}
