<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $storeKeeper = Role::create(['name' => 'store_keeper']);
        // $viewSectionsPermission = Permission::where('name', 'view_sections')->first();
        // $storeKeeper->givePermissionTo($viewSectionsPermission);
    }
}
