<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::UpdateOrCreate(
            ['name'=> 'galih'],
            [
                'email' => 'galih@gmail.com',
                'password' => bcrypt('qwerty1234')
            ]
        );

        $user->roles()->sync([
            1=>[
                'start_date' => now(),
                'end_date' => null,
            ]
        ]);
    }
}
