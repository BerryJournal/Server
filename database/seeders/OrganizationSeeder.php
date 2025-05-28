<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("organizations")->insert([[
            'id' => fake()->uuid(),
            "name"=> "BerryCollage",
            "tariff_id" => 2
        ],
    ]);
    }
}
