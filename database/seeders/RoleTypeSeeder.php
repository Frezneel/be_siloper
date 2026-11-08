<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoleType;

class RoleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleTypes = [
            ['name' => 'SYSTEM', 'description' => 'System role type for system-level access'],
            ['name' => 'SUBSCRIBER', 'description' => 'Subscriber role type for benefit access'],
        ];

        foreach ($roleTypes as $roleType) {
            RoleType::updateOrCreate(
                ['name' => $roleType['name']],
                ['description' => $roleType['description']]
            );
        }
    }
}
