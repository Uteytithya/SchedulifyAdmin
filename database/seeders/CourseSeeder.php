<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Advanced Algorithms C++',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Basic Robotic',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Computer Architecture',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Web Design - UI/UX, HTML, CSS',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Soft Skill (Introduction to Effective Business)',
                'credit' => 2,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Seminar On Emerging Technologies',
                'credit' => 1,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Database Analysis and Design',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Software Engineering',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'OOP (Java)',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Operating System',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Frontend Development (JS, React JS)',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Soft Skill (Entrepreneurship)',
                'credit' => 2,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Database Administration',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Introduction to Cybersecurity',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Backend Development (NextJS)',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Automata',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Fundamental of Mobile Development',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Cloud Computing',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Research Methodology',
                'credit' => 2,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Fundamental of Data Science',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Design Thinking',
                'credit' => 2,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Advance Mobile Development',
                'credit' => 2,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Project Management (Jira, Trello, etc.)',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Game Development',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Capstone Project I',
                'credit' => 3,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Soft Skill (Career Readiness)',
                'credit' => 2,
            ]
        ]);
    }
}
