<?php

namespace Database\Seeders;

use Database\Seeders\MinistryDescriptionSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MinistryCategorySeeder::class,
            MinistrySeeder::class,
            SkillSeeder::class,
            MinistryDescriptionSeeder::class,
            DefaultDataSeeder::class,
        ]);
    }
}
