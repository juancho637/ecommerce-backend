<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admin = Role::create(['name' => Role::SUPER_ADMIN]);
        $super_admin->syncPermissions(Permission::all());

        $company_admin = Role::create(['name' => Role::COMPANY_ADMIN]);
        $company_admin->syncPermissions(Permission::all());
    }
}
