<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentGroupSeeder extends Seeder
{
    public function run()
    {
        StudentGroup::insert([
            [    'id' => Str::uuid(),
                'name' => 'GEN1-SoftwareEngineering-Group1',
                'generation_year' => 2023,
                'department' => 'CS', // Must match ENUM values
                'created_at' => now(),
            ],
            [ 'id' => Str::uuid(),
                'name' => 'GEN1-SoftwareEngineering-Group2',
                'generation_year' => 2023,
                'department' => 'CS', // Must match ENUM values
                'created_at' => now(),
            ],
            [ 'id' => Str::uuid(),
                'name' => 'GEN1-DigitalBusiness-Group1',
                'generation_year' => 2023,
                'department' => 'DB', // Must match ENUM values
                'created_at' => now(),
            ],
            [ 'id' => Str::uuid(),
                'name' => 'GEN1-TelecommunicationAndNetworking-Group1',
                'generation_year' => 2023,
                'department' => 'TN', // Must match ENUM values
                'created_at' => now(),
            ],
            [ 'id' => Str::uuid(),
                'name' => 'GEN10-SoftwareEngineering-Group2',
                'generation_year' => 2022,
                'department' => 'CS', // Must match ENUM values
                'created_at' => now(),
            ],
        ]);
    }
}
