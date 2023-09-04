<?php

namespace App\Http\Controllers\Ajax;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class AjaxManageUsersController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        $users = User::select(['id', 'name_ar', 'name_en', 'email', 'user_code', 'created_at'])->get();
        if (auth()->user()->hasPermissionTo('manage_users')) {
            return response()->json([
                view('components.dash.users.index', ['roles' => $permissions, 'users' => $users])->render()
            ]);
        }
        return response()->json([
            view('components.manageUsers.index', ['roles' => $permissions])->render()
        ]);
    }

    public function getUsersData()
    {
        $users = User::select(['id', 'name_ar', 'name_en', 'email', 'user_code', 'created_at']);
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = "<a href='#' data-type='edit-userRole' data-id='$row->id' id='edit-userrole' data-bs-toggle='modal' data-bs-target='#edit-userRole' class='edit btn btn-info btn-sm'>Edit</a>";
                return $btn;
            })
            ->make(true);
    }

    public function update(Request $req)
    {
        $userId = $req->input('user_id');
        $user = User::find($userId);
        $user->permissions()->detach();
        $permissions = config('roles.permissions');
        foreach ($permissions as $permission) {
            if ($req->has($permission)) {
                $permissionData = Permission::where('name', $permission)->first();
                if ($permissionData) {
                    $user->givePermissionTo($permissionData);
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => __('Edited successfully'),
        ]);
    }

    public function userRole(Request $req)
    {
        $userId = $req->input('id');
        $user = User::find($userId);
        return response()->json(['roles' => $user->getAllPermissions()->pluck('name')]);
    }

    /**
     * @return add-new-user.blade View
     */
    public function newUser()
    {
        return response()->json([
            view('components.dash.users.add-new-user')->render()
        ]);
    }

    public function addNewUser(Request $req) {
        $req->validate([
            'name_ar' => 'required|min:4',
            'name_en' => 'required|min:4',
            'email' => 'required|unique:users,email',
            'user_code' => 'required|unique:users,user_code|min:4|max:10|string',
            'password' => 'required|min:8|max:16|confirmed',
        ]);

        $user = new User();
        $user->name_ar = $req->name_ar;
        $user->name_en = $req->name_en;
        $user->email = $req->email;
        $user->user_code = $req->user_code;
        $user->password = bcrypt($req->password);
        $user->allowed_branches = 0;
        $user->default_branch = 0;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => __('User Created successfully'),
            'html' => view('modals.edit-role',['roles' => Permission::all()])->render(),
            'roles' => $user->getAllPermissions()->pluck('name'),
            'user_id' => $user->id
        ]);
    }

}
