<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::doesntHave('roles')->orderBy('created_at', 'desc')->get();
        return view('dashboard.newUser', compact('users'));
    }


    public function getWithoutRoleUserData($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function updateWithoutRoleUserData(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json(['message' => $errors[0]], 422); // Send only the first error
        }

        $user->update($request->only('name', 'email'));

        return response()->json(['message' => 'User updated successfully']);
    }

    public function countUsersWithoutRoles()
    {
        $count = User::doesntHave('roles')->count();
        return response()->json(['count' => $count]);
    }

    public function destroyWithoutRoleUserData($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function getRoles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function assignRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'role' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json(['message' => $errors[0]], 422); // Send only the first error
        }

        $role = Role::findById($request->role);
        $user->syncRoles([$role]);

        return response()->json(['message' => 'Role assigned successfully']);
    }


    public function loadUserView()
    {
        $authUser = Auth::user();
        $superAdminRole = 'super admin'; // or whatever the role name is

        $usersQuery = User::with('roles')
            ->whereHas('roles') // Only users with roles
            ->where('id', '<>', $authUser->id); // Exclude logged-in user

        if (!$authUser->hasRole($superAdminRole)) {
            $usersQuery->whereDoesntHave('roles', function ($query) use ($superAdminRole) {
                $query->where('name', $superAdminRole);
            });
        }

        $users = $usersQuery->orderBy('created_at', 'desc')->get();

        return view('dashboard.users', compact('users'));
    }



    public function getUserData($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json($user);
    }

    public function updateUserData(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json(['message' => $errors[0]], 422); // Send only the first error
        }

        $user->update($request->only('name', 'email'));
        $role = Role::findById($request->role);
        $user->syncRoles([$role]);

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroyUserData($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function getRolesForUser()
    {
        $roles = Role::all();
        return response()->json($roles);
    }


}
