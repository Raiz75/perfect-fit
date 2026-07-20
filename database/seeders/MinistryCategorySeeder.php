<?php

namespace Database\Seeders;

use App\Models\MinistryCategory;
use Illuminate\Database\Seeder;

class MinistryCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Core Ministry'],
            ['name' => 'Support Ministry'],
            ['name' => 'Outreach Ministry'],
            ['name' => 'Creative & Media Ministry'],
            ['name' => 'Care & Healing Ministry'],
            ['name' => 'Special Interest Ministry'],
        ];

        foreach ($categories as $category) {
            MinistryCategory::create($category);
        }
    }
}
