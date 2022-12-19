<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(Product::TYPES),
            'name' => $name = $this->faker->unique()->sentence(1, false),
            'slug' => Str::slug($name, '-'),
            'is_variable' => false,
            'tax' => $this->faker->randomFloat(2, 0, 20),
            'sku' => Str::random(10),
            'price' => $this->faker->numberBetween(10, 100),
            'short_description' => $this->faker->sentence(30),
            'description' => $this->faker->paragraphs(3, true),
            'status_id' => Status::enabled()->value('id'),
            'category_id' => Category::all()->random()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            if (!$product->is_variable && $product->type === Product::PRODUCT_TYPE) {
                $product->stock = 10;
                $product->width = 10;
                $product->height = 10;
                $product->length = 10;
                $product->weight = 10;
            }

            $product->save();
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

    public function isVariable()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_variable' => true,
            ];
        });
    }

    public function typeProduct()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Product::PRODUCT_TYPE,
            ];
        });
    }

    public function typeService()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Product::SERVICE_TYPE,
            ];
        });
    }
}
