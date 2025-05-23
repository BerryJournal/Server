<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("users")->insert([
            'id' => fake()->uuid(),
            "name"=> "Иван",
            "surname"=> "Иван",
            "patronymic"=> "Иван",
            "email"=> "123@123.ru",
            "password"=> bcrypt("123"),
            "role_id"=> "1",
            'isRegister' => 1,
            'organization_id' => fake()->uuid()
        ]);
    }
}
