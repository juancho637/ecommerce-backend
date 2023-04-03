<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Status;
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
        $admin = User::create([
            'name' => env('INITIAL_USER_NAME') ?? 'Administrador ' . env('APP_NAME', 'Laravel'),
            'email' => env('INITIAL_USER_EMAIL') ?? 'admin@scriptforze.com',
            'username' => env('INITIAL_USER_USERNAME') ?? 'admin',
            'password' => Hash::make(env('INITIAL_USER_PASSWORD') ?? 'password'),
            'status_id' => Status::enabled()->value('id'),
            'email_verified_at' => now(),
        ]);
        $admin->syncRoles(Role::admin()->value('id'));

        if (app()->environment() !== 'production') {
            User::factory()
                ->count(20)
                ->roleUser()
                ->create();
        }
    }
}
