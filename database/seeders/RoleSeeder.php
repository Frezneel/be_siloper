<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  Roles
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator role with full access', 'role_type_id' => 1],
            ['name' => 'staff', 'description' => 'Staff role with moderate access', 'role_type_id' => 1],
            ['name' => 'user', 'description' => 'Regular user role with limited access', 'role_type_id' => 1],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                [
                    'description' => $role['description'],
                    'role_type_id' => $role['role_type_id']
                ]
            );
        }
    }
}
