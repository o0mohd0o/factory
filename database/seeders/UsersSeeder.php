<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name_ar' => 'المسؤل',
            'name_en' => 'admin',
            'email' => 'admin@web.com',
            'password' => bcrypt('1234admin'),
            'user_code' => '0001',
        ]);
        User::create([
            'name_ar' => 'مسؤل الخزنة',
            'name_en' => 'storekeeper',
            'email' => 'storekeeper@web.com',
            'password' => bcrypt('1234keeper'),
            'user_code' => '0002',
        ]);
        User::create([
            'name_ar' => 'المكود',
            'name_en' => 'coder',
            'email' => 'coder@web.com',
            'password' => bcrypt('1234coder'),
            'user_code' => '0003',
        ]);
        User::create([
            'name_ar' => 'المحاسب',
            'name_en' => 'accountant',
            'email' => 'accountant@web.com',
            'password' => bcrypt('1234acc'),
            'user_code' => '0004',
        ]);
    }
}
