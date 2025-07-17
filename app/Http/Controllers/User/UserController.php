<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

      //list user
    public function listUser()
    {
        $users = User::with('roles')->get();
        return Inertia::render('Users/UserListPage', ['users' => $users]);
    }

    //user save page
    public function userSavePage(Request $request)
    {
        $userId = $request->user_id;
        $roles = Role::all();
        $users = User::find($userId);
        if ($userId != 0) {
            $users = User::with('roles')->find($userId);
        }
        return Inertia::render('Users/UserSavePage', ['users' => $users, 'roles' => $roles]);
    }

    //create user
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['errors' => $validator->errors()]);
        }

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone

            ];
            $user=User::create($data);
            $user->assignRole($request->role);

            return redirect()->back()->with(['status' => true, 'message' => 'User created successfully']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong' . $e->getMessage()]);
        }
    }

    //update user

    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['errors' => $validator->errors()]);
        }
        try {
            User::where('id', $request->user_id)->update([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'phone' => $request->phone
            ]);
            $user=User::with('roles')->find($request->user_id);
            $userRole = count($user->roles)!=0 ? $user->roles[0]->name : null;
            if ($userRole != 'superadmin' || $userRole == null) {
                $user->syncRoles($request->role);
            }

            return redirect()->back()->with(['status' => true, 'message' => 'User updated successfully']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    //delete user
    public function deleteUser(Request $request)
    {
        $user = User::where('id', $request->user_id)->with('roles')->first();

        $userRole = count($user->roles)!=0 ? $user->roles[0]->name : null;
        if ($userRole == 'superadmin') {

            return redirect()->back()->with(['status' => false, 'message' => 'You can not delete This User']);
        }
        User::where('id', $request->user_id)->delete();
        return redirect()->back()->with(['status' => true, 'message' => 'User deleted successfully']);
    }
}
