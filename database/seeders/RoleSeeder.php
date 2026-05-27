<?php

namespace Database\Seeders;

use App\Models\Register;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::upsert([
            ['name' => 'Developers', 'description' => 'System Admin'],
            ['name' => 'Admin', 'description' => 'System Admin'],
            ['name' => 'User', 'description' => 'Guest Only'],
            ['name' => 'Staff', 'description' => 'Staff Only'],
        ], ['name'], ['description']);

        $adminRole = Role::where('name', 'Admin')->firstOrFail();

        $users = [
            [
                'email' => 'developers@gmail.com',
                'username' => 'developers',
                'gender' => 'male',
                'phone' => '012999555',
            ],
            [
                'email' => 'admin@gmail.com',
                'username' => 'admin',
                'gender' => 'male',
                'phone' => '012222555',
            ],
            [
                'email' => 'user@gmail.com',
                'username' => 'users',
                'gender' => 'male',
                'phone' => '012222555',
            ],
            [
                'email' => 'staff@gmail.com',
                'username' => 'users',
                'gender' => 'male',
                'phone' => '012222555',
            ],
        ];

        foreach ($users as $user) {
            Register::firstOrCreate(
                ['email' => $user['email']],
                [
                    'username' => $user['username'],
                    'gender' => $user['gender'],
                    'phone' => $user['phone'],
                    'password' => Hash::make('password'),
                    'role_id' => $adminRole->id,
                    'status' => 'active',
                    'profile_image' => null,
                    'remember_token' => null,
                ]
            );
        }
    }
}
