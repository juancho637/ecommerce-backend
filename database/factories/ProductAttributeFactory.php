<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->sentence(1, false),
            'type' => $this->faker->randomElement(ProductAttribute::TYPES),
            'status_id' => Status::enabled()->value('id'),
        ];
    }
}
