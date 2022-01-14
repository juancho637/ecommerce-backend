<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use App\Models\Status;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $email = $this->faker->unique()->safeEmail(),
            'username' => explode('@', $email)[0],
            'status_id' => Status::enabled()->value('id'),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function statusDesabled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => Status::disabled()->value('id'),
            ];
        });
    }

    public function roleAdmin()
    {
        return $this->afterCreating(function (User $user) {
            $user->syncRoles(Role::admin()->value('id'));
        });
    }

    public function roleUser()
    {
        return $this->afterCreating(function (User $user) {
            $user->syncRoles(Role::user()->value('id'));
        });
    }
}
