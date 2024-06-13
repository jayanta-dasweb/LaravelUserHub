<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
            'assign role',
            'create user',
            'view users',
            'edit user',
            'delete user',
            'create permission',
            'view permissions',
            'edit permission',
            'delete permission',
            'create role',
            'view roles',
            'edit role',
            'delete role',
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
    }
}
