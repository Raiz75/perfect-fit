<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    protected $fillable = [
        'church_code', 'email', 'name', 'contact_no',
        'music', 'technology', 'writing', 'technical', 'speaking',
        'accounting', 'mentoring', 'bible_knowledge',
        'eligible_ministry', 'ai_interpretation',
        'gender', 'age', 'marital_status',
        'baptized', 'time_in_faith', 'time_of_submission',
    ];
}
