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

                $product->options .= "category_" . $product->category->name;
                $product->options .= "|tag_" . $tags->pluck('name')->implode('|tag_');
                $product->options .= "|color_rojo";
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

                $product->options .= "category_" . $product->category->name;
                $product->options .= "|tag_" . $tags->pluck('name')->implode('|tag_');
                $product->options .= "|color_rojo|color_verde|color_space grey";
                $product->save();
            });

        Product::factory()
            ->count(10)
            ->create()
            ->each(function ($product) {
                $tags = Tag::all()->random(3);

                $product->tags()->sync($tags->pluck('id'));

                $product->options .= "category_" . $product->category->name;
                $product->options .= "|tag_" . $tags->pluck('name')->implode('|tag_');
                $product->save();
            });

        Product::factory()
            ->count(10)
            ->isVariable()
            ->create()
            ->each(function ($product) {
                $tags = Tag::all()->random(3);
                $productAttributeOptions = ProductAttribute::all()
                    ->load('productAttributeOptions')
                    ->random(mt_rand(1, 3))
                    ->pluck('productAttributeOptions')
                    ->collapse()
                    ->pluck('id');

                $product->tags()->sync($tags->pluck('id'));
                $product->productAttributeOptions()->sync($productAttributeOptions);

                $product->options .= "category_" . $product->category->name;
                $product->options .= "|tag_" . $tags->pluck('name')->implode('|tag_');
                $product->save();
            });
    }
}
