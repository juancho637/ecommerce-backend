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
            ->count(1)
            ->create([
                'name' => 'iphone 12',
                'type' => Product::PRODUCT_TYPE,
                'slug' => 'iphone-12',
            ])
            ->each(function ($product) {
                $tags = Tag::all()->random(3);
                $product->tags()->sync($tags->pluck('id'));

                $product->options .= "category:" . $product->category->name;
                $product->options .= "|tag:" . $tags->pluck('name')->implode('|tag:');
                $product->options .= "|color:rojo";
                $product->save();
            });

        Product::factory()
            ->count(1)
            ->create([
                'name' => 'iphone 13',
                'type' => Product::PRODUCT_TYPE,
                'slug' => 'iphone-13',
            ])
            ->each(function ($product) {
                $tags = Tag::all()->random(3);
                $product->tags()->sync($tags->pluck('id'));

                $product->options .= "category:" . $product->category->name;
                $product->options .= "|tag:" . $tags->pluck('name')->implode('|tag:');
                $product->options .= "|color:rojo|color:verde|color:space grey";
                $product->save();
            });

        Product::factory()
            ->count(10)
            ->create()
            ->each(function ($product) {
                $tags = Tag::all()->random(3);

                $product->tags()->sync($tags->pluck('id'));

                $product->options .= "category:" . $product->category->name;
                $product->options .= "|tag:" . $tags->pluck('name')->implode('|tag:');
                $product->save();
            });

        Product::factory()
            ->count(10)
            ->isVariable()
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
