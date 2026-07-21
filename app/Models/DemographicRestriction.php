<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemographicRestriction extends Model
{
    protected $fillable = [
        'user_id', 'ministry_id', 'gender', 'age_min', 'age_max',
        'marital_status', 'baptized', 'time_in_faith',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }
}
