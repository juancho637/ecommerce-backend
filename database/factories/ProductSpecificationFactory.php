<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductSpecificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(1, false),
            'value' => $this->faker->sentence(1, false),
            'status_id' => Status::enabled()->value('id'),
            'product_id' => Product::all()->random()->id,
        ];
    }

    public function product($product)
    {
        return $this->state(function (array $attributes) use ($product) {
            return [
                'product_id' => $product,
            ];
        });
    }

    public function statusDesabled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::disabled()->value('id'),
            ];
        });
    }
}
