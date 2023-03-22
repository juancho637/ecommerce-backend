<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->roleAdmin()
            ->create([
                'name' => env('INITIAL_USER_NAME', 'Administrador ' . env('APP_NAME', 'Laravel')),
                'email' => env('INITIAL_USER_EMAIL', 'admin@scriptforze.com'),
                'username' => env('INITIAL_USER_USERNAME', 'admin'),
                'password' => Hash::make(env('INITIAL_USER_PASSWORD', 'password')),
            ]);

        if (app()->environment() !== 'production') {
            User::factory()
                ->count(20)
                ->roleUser()
                ->create();
        }
    }
}
