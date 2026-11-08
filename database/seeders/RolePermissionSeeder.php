<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Role 1: Admin - has all permissions
        $admin = Role::where('name', 'admin')->first();
        $adminPermissions = Permission::whereIn('name', [
            'SYSTEM:MANAGE_USERS',
            'SYSTEM:VIEW_USERS',
            'SYSTEM:MANAGE_ROLES',
            'SYSTEM:VIEW_ROLES',
            'SYSTEM:MANAGE_PERMISSIONS',
            'SYSTEM:VIEW_PERMISSIONS',
        ])->pluck('id')->toArray();

        if ($admin) {
            $admin->permissions()->sync($adminPermissions);
        }

        // Role 2: Staff - has moderate permissions
        $staff = Role::where('name', 'staff')->first();
        $staffPermissions = Permission::whereIn('name', [
            'SYSTEM:MANAGE_USERS',
            'SYSTEM:VIEW_USERS',
            'SYSTEM:VIEW_ROLES',
            'SYSTEM:VIEW_PERMISSIONS',
        ])->pluck('id')->toArray();

        if ($staff) {
            $staff->permissions()->sync($staffPermissions);
        }

        // Role 3: User - has limited permissions
        $user = Role::where('name', 'user')->first();
        $userPermissions = Permission::whereIn('name', [
            'SYSTEM:VIEW_USERS',
            'SYSTEM:VIEW_ROLES',
            'SYSTEM:VIEW_PERMISSIONS',
        ])->pluck('id')->toArray();

        if ($user) {
            $user->permissions()->sync($userPermissions);
        };

    }
}
