<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Product;
use Illuminate\Database\Seeder;

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
            ->count(10)
            ->create()
            ->each(function ($product) {
                $tags = Tag::all()->random(3)->pluck('id');

                $product->tags()->sync($tags);
            });
    }
}
