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
        $path = 'image/' . md5(random_int(1, 10000000) . microtime()) . '.jpg';

        return [
            'url' => env('APP_URL') . '/storage' . $path,
            'obtainable_type' => $this->faker->sentence(1, false),
            'obtainable_id' => $this->faker->numberBetween(1, 10000000),
            'path' => $path,
            'type_resource' => $this->faker->sentence(1, false),
        ];
    }
}
