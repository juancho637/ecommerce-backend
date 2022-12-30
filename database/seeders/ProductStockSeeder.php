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
        Product::whereHas('productAttributeOptions')->get()->each(function ($product) {
            $productAttributeOptions = $product->load('productAttributeOptions')
                ->productAttributeOptions
                ->groupBy('product_attribute_id');

            $productAttributeOptionIds = $productAttributeOptions->pluck('*.id');

            $combination = collect($productAttributeOptionIds
                ->shift())->crossJoin(...$productAttributeOptionIds);

            $combination->each(function ($combination) use ($product) {
                ProductStock::factory()
                    ->product($product)
                    ->combination($combination)
                    ->create();
            });
        });

        Product::where('is_variable', 0)->get()->each(function ($product) {
            ProductStock::factory()
                ->product($product)
                ->create();
        });
    }
}
