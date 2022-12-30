<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\Product;
use Illuminate\Support\Str;
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
            'price' => $this->faker->numberBetween(10, 100),
            'sku' => Str::random(10),
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
            if ($product->type === Product::PRODUCT_TYPE) {
                $data['stock'] = $this->faker->numberBetween(50, 100);
                $data['width'] = $this->faker->numberBetween(5, 10);
                $data['height'] = $this->faker->numberBetween(5, 10);
                $data['length'] = $this->faker->numberBetween(5, 10);
                $data['weight'] = $this->faker->numberBetween(5, 10);
            }

            $data['product_id'] = $product;
            $data['price'] = $product->price;

            return $data;
        });
    }

    public function combination($combination)
    {
        return $this->afterCreating(function (ProductStock $productStock) use ($combination) {

            $productStock->productAttributeOptions()->sync($combination);
        });
    }
}
