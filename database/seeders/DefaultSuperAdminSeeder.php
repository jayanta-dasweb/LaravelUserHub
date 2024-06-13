<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'create user',
            'view user',
            'edit user',
            'delete user',
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
