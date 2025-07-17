<?php

namespace App\Http\Controllers\Role;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    //role list
    public function roleList()
    {

        $roles = Role::with('permissions')->get();
        return Inertia::render('Roles/RoleListPage', ['roles' => $roles]);
    }

    //role save page
    public function roleSavePage(Request $request)
    {
        $roleId = $request->role_id;
        $permissions = Permission::all();
        $role = Role::where('id', $roleId)->with('permissions')->first();
        return Inertia::render('Roles/RoleSavePage', ['role' => $role, 'permissions' => $permissions]);
    }
    //create role
    public function createRole(Request $request)
    {


        $validation = Validator::make($request->all(), [
            'roleName' => 'required|unique:roles,name',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['errors' => $validation->errors()]);
        }

        $role = Role::create([
            'name' => $request->roleName,
        ]);
        $permissionId = array_map('intval', $request->permissions);
        $role->syncPermissions($permissionId);
        return redirect('/list-role')->with(['status' => true, 'message' => 'Role has been created successfully']);
    }

    //update role
    public function updateRole(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'roleName' => 'required|unique:roles,name,' . $request->role_id,
        ]);

        if ($validation->fails()) {
            return redirect()->back()->with(['errors' => $validation->errors()]);
        }
        $role = Role::where('id', $request->role_id)->first();
        if ($role->name == 'superadmin') {
            return redirect()->back()->with(['status' => false, 'message' => 'Superadmin role cannot be updated']);
        }
        $role->update([
            'name' => $request->roleName,
        ]);
        $permissionId = array_map('intval', $request->permissions);
        $role->syncPermissions($permissionId);
        return redirect('/list-role')->with(['status' => true, 'message' => 'Role has been updated successfully']);
    }

    //delete role
    public function deleteRole(Request $request)
    {
        $role = Role::where('id', $request->role_id)->first();
        if ($role->name == 'superadmin') {
            return redirect()->back()->with(['status' => false, 'message' => 'Superadmin role cannot be deleted']);
        }
        $role->delete();
        return redirect('/list-role')->with(['status' => true, 'message' => 'Role has been deleted successfully']);
    }
}
