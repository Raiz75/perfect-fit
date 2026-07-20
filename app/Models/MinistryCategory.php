<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinistryCategory extends Model
{
    protected $fillable = ['name'];

    public function ministries()
    {
        $this->hasMany(Ministry::class);
    }

    public function interestAndPassionQuestions()
    {
        $this->hasMany(InterestAndPassionQuestion::class);
    }
}
