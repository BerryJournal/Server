<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TariffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("tariffs")->insert([[
            'id' => 1,
            "name"=> "Стандарт",
        ],
        [
            'id' => 2,
            "name"=> "Макс",
        ],
    ]);
    }
}
