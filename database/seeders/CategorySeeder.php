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
            ->count(1)
            ->create();

        Category::factory()
            ->count(2)
            ->withoutParent()
            ->create();

        Category::factory()
            ->count(1)
            ->withParent()
            ->create();
    }
}
