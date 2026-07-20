<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BehavioralQuestion extends Model
{
    protected $fillable = [
        'user_id', 'ministry_id', 'question_number', 'question_en', 'question_tl',
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
