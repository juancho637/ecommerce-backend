<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;

class ProductAttributeOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductAttribute::all()->each(function ($productAttribute) {
            ProductAttributeOption::factory()
                ->count(2)
                ->productAttribute($productAttribute)
                ->create();
        });
    }
}
