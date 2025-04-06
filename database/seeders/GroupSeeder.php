<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('students_groups')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'CS-GEN-11-G1',
                'generation_year' => 2,
                'department' => 'CS',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'CS-GEN-11-G2',
                'generation_year' => 2,
                'department' => 'CS',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'CS-GEN-11-G3',
                'generation_year' => 2,
                'department' => 'CS',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'CS-GEN-11-G4',
                'generation_year' => 2,
                'department' => 'CS',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'CS-GEN-11-G5',
                'generation_year' => 2,
                'department' => 'CS',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'CS-GEN-11-G6',
                'generation_year' => 2,
                'department' => 'CS',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'CS-GEN-10-G1',
                'generation_year' => 3,
                'department' => 'CS',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'CS-GEN-10-G2',
                'generation_year' => 3,
                'department' => 'CS',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'CS-GEN-10-G3',
                'generation_year' => 3,
                'department' => 'CS',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'CS-GEN-10-G4',
                'generation_year' => 3,
                'department' => 'CS',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'CS-GEN-10-G5',
                'generation_year' => 3,
                'department' => 'CS',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'CS-GEN-10-G6',
                'generation_year' => 3,
                'department' => 'CS',
            ],
        ]);
    }
}
