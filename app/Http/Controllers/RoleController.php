<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('dashboard.allRoles', compact('roles'));
    }

    public function getRoleDetails($id)
    {
        $role = Role::with('permissions')->find($id);
        if ($role) {
            return response()->json($role);
        }
        return response()->json(['message' => 'Role not found.'], 404);
    }

    public function updateRole(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($id) {
                    if (Role::where('id', '!=', $id)->whereRaw('LOWER(name) = ?', strtolower($value))->exists()) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
            ],
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        $role->name = $request->name;
        $role->save();

        // Fetch permissions by their IDs
        $permissions = Permission::whereIn('id', $request->permissions)->get();

        // Assign permissions to the role
        $role->syncPermissions($permissions);

        return response()->json(['message' => 'Role updated successfully!']);
    }


    public function loadCreateView()
    {
        return view('dashboard.createRole');
    }

    public function getPermissions()
    {
        $permissions = Permission::all()->groupBy(function ($item) {
            return explode(' ', $item->name)[1]; 
        });

        return response()->json($permissions);
    }

   

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (Role::whereRaw('LOWER(name) = ?', strtolower($value))->exists()) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
            ],
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        $role = Role::create(['name' => $request->name]);

        // Fetch permissions by their IDs
        $permissions = Permission::whereIn('id', $request->permissions)->get();

        // Assign permissions to the role
        $role->syncPermissions($permissions);

        return response()->json(['message' => 'Role created successfully!']);
    }

    public function getRolePermissions(Role $role)
    {
        $permissions = $role->permissions->pluck('id')->toArray();
        return response()->json($permissions);
    }


}
