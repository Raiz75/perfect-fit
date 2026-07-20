<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillRestriction extends Model
{
    protected $fillable = [
        'user_id', 'ministry_id', 'music', 'technology', 'writing',
        'technical', 'speaking', 'accounting', 'mentoring', 'bible_knowledge',
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function ministry()
    {
        $this->belongsTo(Ministry::class);
    }
}
