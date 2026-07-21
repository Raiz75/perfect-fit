<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    protected $fillable = ['name', 'ministry_category_id'];

    public function category()
    {
        return $this->belongsTo(MinistryCategory::class, 'ministry_category_id');
    }

    public function demographicRestrictions()
    {
        return $this->hasMany(DemographicRestriction::class);
    }

    public function skillRestrictions()
    {
        return $this->hasMany(SkillRestriction::class);
    }

    public function behavioralQuestions()
    {
        return $this->hasMany(BehavioralQuestion::class);
    }
}
