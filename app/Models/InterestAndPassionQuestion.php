<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterestAndPassionQuestion extends Model
{
    protected $fillable = [
        'user_id', 'ministry_category_id', 'question_number', 'question_en', 'question_tl',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ministryCategory()
    {
        return $this->belongsTo(MinistryCategory::class);
    }
}
