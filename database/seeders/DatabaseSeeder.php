<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment() === 'production') {
            $this->call([
                StatusSeeder::class,
                CountrySeeder::class,
                StateSeeder::class,
                CitySeeder::class,
                PermissionSeeder::class,
                RoleSeeder::class,
                UserSeeder::class,
            ]);
        } else {
            $this->call([
                StatusSeeder::class,
                CountrySeeder::class,
                StateSeeder::class,
                CitySeeder::class,
                PermissionSeeder::class,
                RoleSeeder::class,
                UserSeeder::class,
                CategorySeeder::class,
                TagSeeder::class,
                ProductAttributeSeeder::class,
                ProductAttributeOptionSeeder::class,
                ProductSeeder::class,
            ]);
        }
    }
}
