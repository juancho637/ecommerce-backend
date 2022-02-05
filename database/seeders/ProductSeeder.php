<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Product;
use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()
            ->count(5)
            ->create()
            ->each(function ($product) {
                $tags = Tag::all()->random(3)->pluck('id');

                $product->tags()->sync($tags);
            });

        Product::factory()
            ->count(5)
            ->create()
            ->each(function ($product) {
                $tags = Tag::all()->random(3)->pluck('id');
                $productAttributeOptions = ProductAttribute::all()
                    ->load('productAttributeOptions')
                    ->random(mt_rand(1, 3))
                    ->pluck('productAttributeOptions')
                    ->collapse()
                    ->pluck('id');

                $product->tags()->sync($tags);
                $product->productAttributeOptions()->sync($productAttributeOptions);
            });
    }
}
