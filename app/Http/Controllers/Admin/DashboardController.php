<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    const MINISTRIES = [
        'Worship (Singing)', 'Worship (Dancing)', 'Worship (Instrument)', 'Prayer',
        'Preaching', 'Discipleship', 'Youth', 'Young Adults', "Men's", "Women's",
        'Family Or Couples', 'Ushering', 'Administrative', 'Finance', 'Marshal',
        'Facilities Maintenance', 'Evangelism', 'Missions', 'Community Service',
        'Visitation', 'Production Tech', 'Creative & Social Media', 'Counseling',
        'Healing & Deliverance', 'Funeral', 'Addiction Recovery', 'Special Needs',
        'Seniors', 'Single Adults',
    ];

    const GENDER_MAP = [1 => 'Male', 2 => 'Female'];

    const MARITAL_MAP = [1 => 'Single', 2 => 'Married'];

    const BAPTIZED_MAP = [1 => 'Yes', 2 => 'No'];

    const FAITH_MAP = [1 => '1+ Week', 2 => '6+ Months', 3 => '1+ Year', 4 => '2+ Years'];

    public function index()
    {
        $user = Auth::user();
        $totalTakers = UserReport::where('church_code', $user->church_code)->count();
        $todayCount = UserReport::where('church_code', $user->church_code)
            ->whereDate('time_of_submission', now()->toDateString())
            ->count();
        $ministries = self::MINISTRIES;

        return view('admin.dashboard', compact('totalTakers', 'todayCount', 'ministries'));
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $query = UserReport::where('church_code', $user->church_code);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($startDate = $request->input('startDate')) {
            $query->whereDate('time_of_submission', '>=', $startDate);
        }
        if ($endDate = $request->input('endDate')) {
            $query->whereDate('time_of_submission', '<=', $endDate);
        }

        if ($gender = $request->input('gender')) {
            $query->where('gender', $gender);
        }
        if ($marital = $request->input('marital')) {
            $query->where('marital_status', $marital);
        }
        if ($baptized = $request->input('baptized')) {
            $query->where('baptized', $baptized);
        }
        if ($faith = $request->input('faith')) {
            $query->where('time_in_faith', $faith);
        }
        if ($age = $request->input('age')) {
            $query->where('age', $age);
        }

        if ($skills = $request->input('skills')) {
            $skillList = explode(',', $skills);
            foreach ($skillList as $skill) {
                $skill = trim($skill);
                if (in_array($skill, ['music', 'technology', 'writing', 'technical', 'speaking', 'accounting', 'mentoring', 'bible_knowledge'])) {
                    $query->where($skill, 1);
                }
            }
        }

        if ($ministries = $request->input('ministries')) {
            $ministryList = explode(',', $ministries);
            $query->where(function ($q) use ($ministryList) {
                foreach ($ministryList as $ministry) {
                    $ministry = trim($ministry);
                    $q->orWhere('eligible_ministry', 'like', "%{$ministry}%");
                }
            });
        }

        $reports = $query->orderBy('time_of_submission', 'desc')->get()->map(function ($r) {
            return [
                'time_of_submission' => $r->time_of_submission,
                'email' => $r->email,
                'name' => $r->name,
                'music' => $r->music,
                'technology' => $r->technology,
                'writing' => $r->writing,
                'technical' => $r->technical,
                'speaking' => $r->speaking,
                'accounting' => $r->accounting,
                'mentoring' => $r->mentoring,
                'bible_knowledge' => $r->bible_knowledge,
                'eligible_ministry' => $r->eligible_ministry,
                'gender' => self::GENDER_MAP[$r->gender] ?? '—',
                'age' => $r->age ?? '—',
                'marital' => self::MARITAL_MAP[$r->marital_status] ?? '—',
                'baptized' => self::BAPTIZED_MAP[$r->baptized] ?? '—',
                'timeInFaith' => self::FAITH_MAP[$r->time_in_faith] ?? '—',
            ];
        });

        $totalTakers = UserReport::where('church_code', $user->church_code)->count();
        $todayCount = UserReport::where('church_code', $user->church_code)
            ->whereDate('time_of_submission', now()->toDateString())
            ->count();

        return response()->json([
            'userReports' => $reports,
            'userTakeCount' => $totalTakers,
            'userTodayCount' => $todayCount,
        ]);
    }
}
