<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DefaultSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the default user
        $user = User::create([
            'name' => 'Jayanta Das',
            'email' => 'jayantadas.dev@gmail.com',
            'password' => bcrypt('123456789'),
        ]);

        // Create the "super admin" role
        $role = Role::create(['name' => 'super admin']);

        // Create default permissions
        $permissions = [
            'view new users',
            'edit new user',
            'delete new user',
            'assign role',
            'view users',
            'edit user',
            'delete user',
            'create role',
            'view roles',
            'edit role',
            'assign permissions',
            'upload excel',
            'view excel files',
            'edit excel file',
            'delete excel file',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign all permissions to the "super admin" role
        $role->syncPermissions(Permission::all());

        // Assign the "super admin" role to the user
        $user->assignRole($role);

        // Define dummy roles and their permissions
        $dummyRoles = [
            'role1' => ['view new users', 'edit new user', 'view users', 'edit user'],
            'role2' => ['view new users', 'view users', 'delete user'],
            'role3' => ['view users', 'edit user', 'delete user', 'view excel files'],
            'role4' => ['view users', 'upload excel', 'view excel files', 'edit excel file'],
            'role5' => ['view excel files', 'delete excel file']
        ];

        foreach ($dummyRoles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);

            // Assign the permissions to the role directly
            $role->syncPermissions($rolePermissions);
        }

        // Create 500 users without any roles
        for ($i = 1; $i <= 500; $i++) {
            User::create([
                'name' => "Test User $i",
                'email' => "test$i@gmail.com",
                'password' => Hash::make('123456789'),
            ]);
        }
    }
}
