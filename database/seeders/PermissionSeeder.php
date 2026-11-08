<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User management permissions
            ['name' => 'SYSTEM:MANAGE_USERS', 'description' => 'Permission to manage users'],
            ['name' => 'SYSTEM:VIEW_USERS', 'description' => 'Permission to view users'],

            // Role management permissions
            ['name' => 'SYSTEM:MANAGE_ROLES', 'description' => 'Permission to manage roles'],
            ['name' => 'SYSTEM:VIEW_ROLES', 'description' => 'Permission to view roles'],

            // Permission management permissions
            ['name' => 'SYSTEM:MANAGE_PERMISSIONS', 'description' => 'Permission to manage permissions'],
            ['name' => 'SYSTEM:VIEW_PERMISSIONS', 'description' => 'Permission to view permissions'],

            // Subscriber permissions
            ['name' => 'SUBSCRIBER:VIEW_CARD_SILVER_CONTENT', 'description' => 'Permission to get a discount on the silver card'],
            ['name' => 'SUBSCRIBER:MANAGE_CARD_SILVER_CONTENT', 'description' => 'Permission to get a discount on the silver card'],
            ['name' => 'SUBSCRIBER:VIEW_CARD_GOLD_CONTENT', 'description' => 'Permission to get a discount on the gold card'],
            ['name' => 'SUBSCRIBER:MANAGE_CARD_GOLD_CONTENT', 'description' => 'Permission to get a discount on the gold card'],
            ['name' => 'SUBSCRIBER:VIEW_CARD_PLATINUM_CONTENT', 'description' => 'Permission to get a discount on the platinum card'],
            ['name' => 'SUBSCRIBER:MANAGE_CARD_PLATINUM_CONTENT', 'description' => 'Permission to get a discount on the platinum card'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']], // Parameter ke-1: Kondisi pencarian (kolom unik)
                ['description' => $permission['description']] // Parameter ke-2: Data yang dibuat atau diperbarui
            );
        }
    }
}
