<?php

namespace Database\Factories;

use App\Models\Status;
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
            'name' => $name = $this->faker->unique()->sentence(1, false),
            'slug' => Str::slug($name, '-'),
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
}
