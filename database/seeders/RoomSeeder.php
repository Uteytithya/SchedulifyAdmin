<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rooms')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'A001',
                'capacity' => 48,
                'floor' => 0,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A002',
                'capacity' => 78,
                'floor' => 0,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A007',
                'capacity' => 32,
                'floor' => 0,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A008',
                'capacity' => 32,
                'floor' => 0,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A009',
                'capacity' => 32,
                'floor' => 0,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A010',
                'capacity' => 34,
                'floor' => 0,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A101',
                'capacity' => 66,
                'floor' => 1,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A102',
                'capacity' => 60,
                'floor' => 1,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A111',
                'capacity' => 42,
                'floor' => 1,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A112',
                'capacity' => 28,
                'floor' => 1,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A113',
                'capacity' => 28,
                'floor' => 1,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A114',
                'capacity' => 28,
                'floor' => 1,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A201',
                'capacity' => 48,
                'floor' => 2,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A203',
                'capacity' => 32,
                'floor' => 2,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A204',
                'capacity' => 79,
                'floor' => 2,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A205',
                'capacity' => 32,
                'floor' => 2,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A206',
                'capacity' => 42,
                'floor' => 2,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A207',
                'capacity' => 36,
                'floor' => 2,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A208',
                'capacity' => 42,
                'floor' => 2,
                'is_active' => false,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'A209',
                'capacity' => 50,
                'floor' => 2,
                'is_active' => false,
            ],
        ]);
    }
}
