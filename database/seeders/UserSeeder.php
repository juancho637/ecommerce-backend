<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

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
                'name' => 'Administrador Ecommerce',
                'email' => 'admin@scriptforze.com',
                'username' => 'admin',
            ]);

        if (app()->environment() !== 'production') {
            User::factory()
                ->count(20)
                ->roleUser()
                ->create();
        }
    }
}
