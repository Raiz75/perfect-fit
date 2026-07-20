<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            ['name' => 'Music'],
            ['name' => 'Technology'],
            ['name' => 'Writing'],
            ['name' => 'Technical'],
            ['name' => 'Speaking'],
            ['name' => 'Accounting'],
            ['name' => 'Mentoring'],
            ['name' => 'Bible Knowledge'],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
