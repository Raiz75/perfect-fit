<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    protected $fillable = ['name', 'ministry_category_id'];

    public function category()
    {
        $this->belongsTo(MinistryCategory::class, 'ministry_category_id');
    }

    public function demographicRestrictions()
    {
        $this->hasMany(DemographicRestriction::class);
    }

    public function skillRestrictions()
    {
        $this->hasMany(SkillRestriction::class);
    }

    public function behavioralQuestions()
    {
        $this->hasMany(BehavioralQuestion::class);
    }
}
