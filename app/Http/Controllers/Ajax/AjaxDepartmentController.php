<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Services\DepartmentService;
use App\Models\Department;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AjaxDepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return response()->json([
            view('components.department.index', [
                'departments' => $departments,
            ])->render()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:departments,name'
        ]);

        $department = Department::create($data);

        return response()->json([
            'status' => 'success',
            'message' =>  __('Department Created Successfully'),
            'department' => $department,
        ]);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'id' => 'required|exists:departments',
            'name' => 'required|string|unique:departments,name,'.$request->id,
        ]);

        try {
            $department = Department::findOrFail($data['id']);
            $department->update($data);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Department Not Found',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' =>  __('Department updated successfully'),
        ]);
    }

    public function edit(Department $department)
    {
        return response()->json([
            view('components.department.edit', [
                'department' => $department,
            ])->render()
        ]);
    }


    function delete(Department $department)
    {
        if (DepartmentService::usedBefore($department)) {
            return response()->json([
                'status' => 'error',
                'message' => __('Sorry you can\'t delete. This Department Has been used Before'),
            ], 403);
        }
        $department->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Department has been deleted',
        ]);
    }
}
