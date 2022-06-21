<?php

namespace Database\Factories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
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
            'name' => $this->faker->sentence(1, false),
            'short_name' => ucwords($this->faker->lexify('???')),
            'phone_code' => '+' . $this->faker->numerify('###'),
        ];
    }
}
