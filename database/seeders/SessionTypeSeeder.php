<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SessionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("session_types")->insert([
            [
                "id" => Str::uuid(),
                "name" => "Theory",
            ],
            [
                "id" => Str::uuid(),
                "name" => "Lab",
            ],
            [
                "id" => Str::uuid(),
                "name" => "Seminar",
            ]
        ]);
    }
}
