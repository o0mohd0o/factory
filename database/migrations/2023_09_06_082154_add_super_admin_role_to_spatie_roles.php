<?php

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddSuperAdminRoleToSpatieRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Role::firstOrCreate(['name'=>'Super Admin']);

            $permissions = config("roles.permissions");
            // create and insert permission
            foreach($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }

            $superAdmin = User::firstOrCreate(['user_code' => '0000'],
            [
                'email' => 'khabeer@khabeer.com',
                'name_ar' => 'الدعم الفنى',
                'name_en' => 'Support',
                'job_name' => 'Super Admin',
                'password' => Hash::make(env('SUPER_ADMIN_PASSWORD'))
            ]);

            $superAdmin->assignRole('Super Admin');
            $permissions = config("roles.permissions");

            foreach ($permissions as $permission) {
                $permissionData = Permission::where('name', $permission)->first();
                $superAdmin->givePermissionTo($permissionData);
            }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::where('name', 'Super Admin')->delete();
        User::where('user_code', '0000')->delete();
    }
}
