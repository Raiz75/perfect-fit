<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinistryCategory extends Model
{
    protected $fillable = ['name'];

    public function ministries()
    {
        return $this->hasMany(Ministry::class);
    }

    public function interestAndPassionQuestions()
    {
        return $this->hasMany(InterestAndPassionQuestion::class);
    }
}
