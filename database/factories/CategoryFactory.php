<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
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
            'status_id' => Status::enabled()->value('id'),
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

    public function withoutParent()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => Category::whereNull('parent_id')->get()->random()->id,
            ];
        });
    }

    public function withParent()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => Category::whereNotNull('parent_id')->get()->random()->id,
            ];
        });
    }
}
