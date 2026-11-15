<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $user = User::UpdateOrCreate(
            ['name'=> 'administrator'],
            [
                'email' => 'administrator@siloper.local',
                'password' => Hash::make('administrator'),
                'must_change_password' => true,
            ]
        );

        $user->roles()->sync([
            $adminRole->id => [
                'is_active' => true,
                'start_date' => now(),
                'end_date' => null,
            ]
        ]);
    }
}
