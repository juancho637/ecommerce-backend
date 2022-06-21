<?php

namespace Database\Factories;

use App\Models\State;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(1, false),
            'status_id' => Status::enabled()->value('id'),
            'state_id' => State::all()->random()->id,
        ];
    }
}
