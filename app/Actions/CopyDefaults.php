<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class CopyDefaults
{
    /**
     * Copy all default records from admin template (user_id=1) to a new user.
     */
    public function handle(int $newUserId): void
    {
        $tables = [
            'demographic_restrictions' => ['user_id', 'ministry_id', 'gender', 'age_min', 'age_max', 'marital_status', 'baptized', 'time_in_faith'],
            'skill_restrictions' => ['user_id', 'ministry_id', 'music', 'technology', 'writing', 'technical', 'speaking', 'accounting', 'mentoring', 'bible_knowledge'],
            'skill_questions' => ['user_id', 'skill_id', 'question_number', 'question_en', 'question_tl'],
            'interest_and_passion_questions' => ['user_id', 'ministry_category_id', 'question_number', 'question_en', 'question_tl'],
            'behavioral_questions' => ['user_id', 'ministry_id', 'question_number', 'question_en', 'question_tl'],
        ];

        foreach ($tables as $table => $columns) {
            $rows = DB::table($table)->where('user_id', 1)->get();
            foreach ($rows as $row) {
                $insert = [];
                foreach ($columns as $col) {
                    $insert[$col] = $col === 'user_id' ? $newUserId : $row->$col;
                }
                DB::table($table)->insert($insert);
            }
        }
    }
}
