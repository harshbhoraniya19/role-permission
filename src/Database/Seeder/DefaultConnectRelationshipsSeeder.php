<?php

namespace harshbhoraniya19\RolePermission\Seeders;

use harshbhoraniya19\RolePermission\Models\Permission;
use harshbhoraniya19\RolePermission\Models\Role;
use Illuminate\Database\Seeder;

class DefaultConnectRelationshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Get Available Permissions.
         */
        $permissions = Permission::all();

        /**
         * Attach Permissions to Roles.
         */
        $roleAdmin = Role::where('name', '=', 'Admin')->first();
        foreach ($permissions as $permission) {
            $roleAdmin->attachPermission($permission);
        }
    }
}