<?php

namespace Database\Factories;

use App\Models\SocialNetwork;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialNetworkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'provider' => $this->faker->randomElement(SocialNetwork::PROVIDERS),
            'provider_id' => md5(random_int(1, 10000000) . microtime()),
            'user_id' => User::all()->random()->id,
        ];
    }
}
