<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Roles
        Permission::create(['name' => Permission::ROLES_VIEW]);
        Permission::create(['name' => Permission::ROLES_SHOW]);
        Permission::create(['name' => Permission::ROLES_CREATE]);
        Permission::create(['name' => Permission::ROLES_EDIT]);
        Permission::create(['name' => Permission::ROLES_DELETE]);
    }
}
