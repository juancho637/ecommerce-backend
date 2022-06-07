<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductAttribute::factory()
            ->create([
                'name' => 'color',
                'type' => ProductAttribute::COLOR_TYPE,
            ]);
        ProductAttribute::factory()
            ->create([
                'name' => 'talla',
                'type' => ProductAttribute::SELECT_TYPE,
            ]);
        ProductAttribute::factory()
            ->create([
                'name' => 'peso',
                'type' => ProductAttribute::SELECT_TYPE,
            ]);
        ProductAttribute::factory()
            ->create([
                'name' => 'capacidad',
                'type' => ProductAttribute::SELECT_TYPE,
            ]);
        ProductAttribute::factory()
            ->create([
                'name' => 'altura',
                'type' => ProductAttribute::SELECT_TYPE,
            ]);
    }
}
