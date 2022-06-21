<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'path' => 'image/' . md5(random_int(1, 10000000) . microtime()) . '.jpg',
            'type_resource' => $this->faker->sentence(1, false),
        ];
    }
}
