<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
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
                'name' => 'Basic',
                'price' => 70000,
                'description' => 'Basic',
                'frequency' => 1,
                'limit_book' => 3
            ]
        ];

        MembershipPlan::insert($data);
    }
}
