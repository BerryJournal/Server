<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("roles")->insert([
            ['name'=>"Гл. Администратор"],
            ['name'=>"Администратор"],
            ['name'=>"Преподаватель"],
            ['name'=>"Студент"],
        ]);
    }
}
