<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        $superAdmin = User::create([
            'name_ar' => ' المدير',
            'name_en' => 'Super Admin',
            'email' => 'super@admin.com',
            'password' => bcrypt('01010909Aa'),
            'user_code' => '0000',
            'allowed_branches' => 0,
            'default_branch' => 0
        ]);
    }
}
