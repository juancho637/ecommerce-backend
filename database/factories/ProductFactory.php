<?php

namespace Database\Factories;

use App\Models\Tag;
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

    public function statusDesabled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::disabled()->value('id'),
            ];
        });
    }

    public function statusGeneralStep()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::productGeneralStep()->value('id'),
            ];
        });
    }

    public function statusStocksStep()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::productStocksStep()->value('id'),
            ];
        });
    }

    public function statusSpecificationsStep()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::productSpecificationsStep()->value('id'),
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

    public function withTags($numberOfTags = 1)
    {
        return $this->afterCreating(function (Product $product) use ($numberOfTags) {
            $tags = Tag::all()->random($numberOfTags)->pluck('id');

            $product->tags()->attach($tags);
        });
    }
}
