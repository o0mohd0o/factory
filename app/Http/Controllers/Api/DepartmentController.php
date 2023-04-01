<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function getMainDepartment()
    {
        $mainDepartment = Department::where('main_department', true)->get();

        return response()->json([
            'departments' => $mainDepartment->pluck('name', 'id')
        ]);
    }

    
}
