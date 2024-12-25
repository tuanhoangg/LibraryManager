<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
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
                'description' => 'Admin',
            ],
            [
                'id' => 2,
                'name' => 'Thủ thư',
                'description' => 'Thủ thư',
            ],
            [
                'id' => 3,
                'name' => 'Người dùng',
                'description' => 'Người dùng',
            ],
        ];

        Roles::insert($data);
    }
}
