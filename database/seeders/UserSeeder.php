<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $data = [
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('1'),
                'role_id' => Roles::ROLE_ADMIN,
                'email_verified_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Librarian',
                'email' => 'librarian@gmail.com',
                'password' => Hash::make('1'),
                'role_id' => Roles::ROLE_LIBRARIAN,
                'email_verified_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => Hash::make('1'),
                'role_id' => Roles::ROLE_USER,
                'email_verified_at' => now(),
            ],
        ];

        User::insert($data);
    }
}
