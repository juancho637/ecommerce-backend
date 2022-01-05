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
        $companyType = Permission::COMPANY;
        $agencyType = Permission::AGENCY;

        // Permissions
        Permission::create(['name' => Permission::PERMISSIONS_VIEW, 'types' => "$companyType,$agencyType"]);

        // Roles
        Permission::create(['name' => Permission::ROLES_VIEW, 'types' => "$companyType,$agencyType"]);
        Permission::create(['name' => Permission::ROLES_SHOW, 'types' => "$companyType,$agencyType"]);
        Permission::create(['name' => Permission::ROLES_CREATE, 'types' => "$companyType,$agencyType"]);
        Permission::create(['name' => Permission::ROLES_EDIT, 'types' => "$companyType,$agencyType"]);
        Permission::create(['name' => Permission::ROLES_DELETE, 'types' => "$companyType,$agencyType"]);
    }
}
