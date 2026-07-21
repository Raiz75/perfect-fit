<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalTakers = UserReport::where('church_code', $user->church_code)->count();
        $todayCount = UserReport::where('church_code', $user->church_code)
            ->whereDate('time_of_submission', now()->toDateString())
            ->count();

        return view('admin.dashboard', compact('totalTakers', 'todayCount'));
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $query = UserReport::where('church_code', $user->church_code);

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Date range
        if ($startDate = $request->input('startDate')) {
            $query->whereDate('time_of_submission', '>=', $startDate);
        }
        if ($endDate = $request->input('endDate')) {
            $query->whereDate('time_of_submission', '<=', $endDate);
        }

        // Demographic filters
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

        // Skill filters (comma-separated)
        if ($skills = $request->input('skills')) {
            $skillList = explode(',', $skills);
            foreach ($skillList as $skill) {
                $skill = trim($skill);
                if (in_array($skill, ['music', 'technology', 'writing', 'technical', 'speaking', 'accounting', 'mentoring', 'bible_knowledge'])) {
                    $query->where($skill, 1);
                }
            }
        }

        // Ministry filter (comma-separated)
        if ($ministries = $request->input('ministries')) {
            $ministryList = explode(',', $ministries);
            $query->where(function ($q) use ($ministryList) {
                foreach ($ministryList as $ministry) {
                    $ministry = trim($ministry);
                    $q->orWhere('eligible_ministry', 'like', "%{$ministry}%");
                }
            });
        }

        $reports = $query->orderBy('time_of_submission', 'desc')->get();

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