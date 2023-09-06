<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds to insert default permission and rules for application
     *
     * @return void
     */

    public function run()
    {
        $permissions = config("roles.permissions");
        // create and insert permission
        foreach($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
