<?php

namespace Database\Seeders;

use App\Models\Ministry;
use Illuminate\Database\Seeder;

class MinistrySeeder extends Seeder
{
    public function run(): void
    {
        $ministries = [
            ['name' => 'Worship (Singing)', 'ministry_category_id' => 1],
            ['name' => 'Worship (Dancing)', 'ministry_category_id' => 1],
            ['name' => 'Worship (Instrument)', 'ministry_category_id' => 1],
            ['name' => 'Prayer', 'ministry_category_id' => 1],
            ['name' => 'Preaching', 'ministry_category_id' => 1],
            ['name' => 'Discipleship', 'ministry_category_id' => 1],
            ['name' => 'Youth', 'ministry_category_id' => 1],
            ['name' => 'Young Adults', 'ministry_category_id' => 1],
            ['name' => "Men's", 'ministry_category_id' => 1],
            ['name' => "Women's", 'ministry_category_id' => 1],
            ['name' => 'Family Or Couples', 'ministry_category_id' => 1],
            ['name' => 'Ushering', 'ministry_category_id' => 2],
            ['name' => 'Administrative', 'ministry_category_id' => 2],
            ['name' => 'Finance', 'ministry_category_id' => 2],
            ['name' => 'Marshal', 'ministry_category_id' => 2],
            ['name' => 'Facilities Maintenance', 'ministry_category_id' => 2],
            ['name' => 'Evangelism', 'ministry_category_id' => 3],
            ['name' => 'Missions', 'ministry_category_id' => 3],
            ['name' => 'Community Service', 'ministry_category_id' => 3],
            ['name' => 'Visitation', 'ministry_category_id' => 3],
            ['name' => 'Production Tech', 'ministry_category_id' => 4],
            ['name' => 'Creative & Social Media', 'ministry_category_id' => 4],
            ['name' => 'Counseling', 'ministry_category_id' => 5],
            ['name' => 'Healing & Deliverance', 'ministry_category_id' => 5],
            ['name' => 'Funeral', 'ministry_category_id' => 5],
            ['name' => 'Addiction Recovery', 'ministry_category_id' => 5],
            ['name' => 'Special Needs', 'ministry_category_id' => 5],
            ['name' => 'Seniors', 'ministry_category_id' => 6],
            ['name' => 'Single Adults', 'ministry_category_id' => 6],
        ];

        foreach ($ministries as $ministry) {
            Ministry::create($ministry);
        }
    }
}
