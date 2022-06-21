<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
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
            'country_id' => Country::all()->random()->id,
            'name' => $this->faker->sentence(1, false),
        ];
    }
}
