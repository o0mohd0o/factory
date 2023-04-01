<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class MainDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::create([
            'name' => 'الخزينة الرئيسية',
            'main_department' => true,
        ]);
    }
}
