<?php

namespace App\Http\Controllers;

use App\Models\DemographicRestriction;
use App\Models\MinistryCategory;
use App\Models\Skill;
use App\Models\SkillRestriction;

class FrontendController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function ministries()
    {
        $categories = MinistryCategory::with('ministries')->orderBy('id')->get();
        $demographicRestrictions = DemographicRestriction::where('user_id', 1)->get()->keyBy('ministry_id');
        $skillRestrictions = SkillRestriction::where('user_id', 1)->get()->keyBy('ministry_id');
        $skills = Skill::orderBy('id')->get();

        return view('ministries', compact('categories', 'demographicRestrictions', 'skillRestrictions', 'skills'));
    }

    public function privacyPolicy()
    {
        return view('privacy-policy');
    }
}
