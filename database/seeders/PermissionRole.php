<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRole extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guardName = 'web';

        // Create permissions
        $permissions = [
            'manage contacts',
            'manage custom fields'
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => $guardName]);
        }

        // Create roles
        $admin = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => $guardName]
        );
        $user = Role::firstOrCreate(
            ['name' => 'User', 'guard_name' => $guardName]
        );
        // Assign permissions to roles
        $admin->syncPermissions(Permission::all());
        $user->syncPermissions(Permission::all());
    }
}
