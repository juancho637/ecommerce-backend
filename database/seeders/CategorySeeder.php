<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()
            ->count(15)
            ->create();

        Category::factory()
            ->count(10)
            ->withoutParent()
            ->create();

        Category::factory()
            ->count(10)
            ->withParent()
            ->create();
    }
}
