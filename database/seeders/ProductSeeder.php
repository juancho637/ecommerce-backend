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
                'short_description' => 'this is a short example description',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque vel facilisis neque. Donec placerat ligula mi, convallis faucibus velit gravida eget. Phasellus at dui et quam luctus imperdiet malesuada et nisi. Nulla erat enim, egestas pharetra volutpat convallis, gravida sed enim. Vestibulum tincidunt ullamcorper elit, at vestibulum nulla condimentum at. Donec aliquam vitae nisl sit amet gravida. Morbi posuere tellus dictum ante efficitur, quis consectetur magna maximus. Praesent at augue maximus, tincidunt mi at, condimentum felis. Cras turpis nulla, pulvinar eget viverra eu, vehicula sit amet sapien. Integer porta non elit nec lobortis.',
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
                'short_description' => 'this is a short example description',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque vel facilisis neque. Donec placerat ligula mi, convallis faucibus velit gravida eget. Phasellus at dui et quam luctus imperdiet malesuada et nisi. Nulla erat enim, egestas pharetra volutpat convallis, gravida sed enim. Vestibulum tincidunt ullamcorper elit, at vestibulum nulla condimentum at. Donec aliquam vitae nisl sit amet gravida. Morbi posuere tellus dictum ante efficitur, quis consectetur magna maximus. Praesent at augue maximus, tincidunt mi at, condimentum felis. Cras turpis nulla, pulvinar eget viverra eu, vehicula sit amet sapien. Integer porta non elit nec lobortis.',
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
            ->count(5)
            ->create()
            ->each(function ($product) {
                $tags = Tag::all()->random(3);

                $product->tags()->sync($tags->pluck('id'));

                $product->options .= "category:" . $product->category->name;
                $product->options .= "|tag:" . $tags->pluck('name')->implode('|tag:');
                $product->save();
            });

        Product::factory()
            ->count(5)
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
