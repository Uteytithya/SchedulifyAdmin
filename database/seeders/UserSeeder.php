<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
    DB::table('users')->insert([
        [
            'id'=> Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ],
        [
            'id'=> Str::uuid(),
            'name' => 'Nuon Uteytithya',
            'email' => 'tithya@example.com',
            'password' => bcrypt('tithya123'),
            'role' => 'user',
        ],
        [
            'id'=> Str::uuid(),
            'name' => 'Ou Sengly',
            'email' => 'sengly@example.com',
            'password' => bcrypt('sengly123'),
            'role' => 'user',
        ],
        [
            'id'=> Str::uuid(),
            'name' => 'Korn Visal',
            'email' => 'visal@example.com',
            'password' => bcrypt('visal123'),
            'role' => 'user',
        ],
        [
            'id'=> Str::uuid(),
            'name' => 'Nangdy Panhar',
            'email' => 'panhar@example.com',
            'password' => bcrypt('panhar123'),
            'role' => 'user',
        ],
        [
            'id'=> Str::uuid(),
            'name' => 'Lim Penghuot',
            'email' => 'penghuot@example.com',
            'password' => bcrypt('penghuot123'),
            'role' => 'user',
        ],
        [
            'id'=> Str::uuid(),
            'name' => 'Oeurn Lee Sinh',
            'email' => 'sinh@example.com',
            'password' => bcrypt('sinh123'),
            'role' => 'user',
        ],
    ]);
    }
}
