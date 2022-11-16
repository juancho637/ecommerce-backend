<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductStockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status_id' => Status::enabled()->value('id'),
            'product_id' => Product::all()->random()->id,
            'stock' => $this->faker->numberBetween(50, 100),
            'min_stock' => 10,
            'price' => $this->faker->numberBetween(10, 100),
            'tax' => $this->faker->randomFloat(2, 0, 20),
            'sku' => sha1(time()),
        ];
    }

    public function statusDesabled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::disabled()->value('id'),
            ];
        });
    }

    public function product($product)
    {
        return $this->state(function (array $attributes) use ($product) {
            return [
                'product_id' => $product,
            ];
        });
    }

    public function combination($combination)
    {
        return $this->afterCreating(function (ProductStock $productStock) use ($combination) {

            $productStock->productAttributeOptions()->sync($combination);
        });
    }
}
