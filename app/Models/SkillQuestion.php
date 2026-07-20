<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillQuestion extends Model
{
    protected $fillable = [
        'user_id', 'skill_id', 'question_number', 'question_en', 'question_tl',
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function skill()
    {
        $this->belongsTo(Skill::class);
    }
}
