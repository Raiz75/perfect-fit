<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemographicRestriction;
use App\Models\SkillRestriction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestrictionController extends Controller
{
    public function demographics()
    {
        $restrictions = DemographicRestriction::with('ministry')
            ->where('user_id', Auth::id())
            ->orderBy('ministry_id')
            ->get();

        return view('admin.restrictions.demographics', compact('restrictions'));
    }

    public function skills()
    {
        $restrictions = SkillRestriction::with('ministry')
            ->where('user_id', Auth::id())
            ->orderBy('ministry_id')
            ->get();

        return view('admin.restrictions.skills', compact('restrictions'));
    }

    public function updateDemographics(Request $request)
    {
        $data = $request->validate([
            'restrictions' => 'required|array',
            'restrictions.*.id' => 'required|integer',
            'restrictions.*.gender' => 'required|in:0,1,2',
            'restrictions.*.age_min' => 'required|integer|min:1|max:99',
            'restrictions.*.age_max' => 'required|integer|min:1|max:99',
            'restrictions.*.marital_status' => 'required|in:0,1,2',
            'restrictions.*.baptized' => 'required|in:1,2',
            'restrictions.*.time_in_faith' => 'required|in:1,2,3,4',
        ]);

        $userId = Auth::id();

        DB::transaction(function () use ($data, $userId) {
            foreach ($data['restrictions'] as $item) {
                DemographicRestriction::where('id', $item['id'])
                    ->where('user_id', $userId)
                    ->update([
                        'gender' => $item['gender'],
                        'age_min' => $item['age_min'],
                        'age_max' => $item['age_max'],
                        'marital_status' => $item['marital_status'],
                        'baptized' => $item['baptized'],
                        'time_in_faith' => $item['time_in_faith'],
                    ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Demographic restrictions updated successfully.',
        ]);
    }

    public function updateSkills(Request $request)
    {
        $data = $request->validate([
            'restrictions' => 'required|array',
            'restrictions.*.id' => 'required|integer',
            'restrictions.*.music' => 'required|in:0,1',
            'restrictions.*.technology' => 'required|in:0,1',
            'restrictions.*.writing' => 'required|in:0,1',
            'restrictions.*.technical' => 'required|in:0,1',
            'restrictions.*.speaking' => 'required|in:0,1',
            'restrictions.*.accounting' => 'required|in:0,1',
            'restrictions.*.mentoring' => 'required|in:0,1',
            'restrictions.*.bible_knowledge' => 'required|in:0,1',
        ]);

        $userId = Auth::id();

        DB::transaction(function () use ($data, $userId) {
            foreach ($data['restrictions'] as $item) {
                SkillRestriction::where('id', $item['id'])
                    ->where('user_id', $userId)
                    ->update([
                        'music' => $item['music'],
                        'technology' => $item['technology'],
                        'writing' => $item['writing'],
                        'technical' => $item['technical'],
                        'speaking' => $item['speaking'],
                        'accounting' => $item['accounting'],
                        'mentoring' => $item['mentoring'],
                        'bible_knowledge' => $item['bible_knowledge'],
                    ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Skill restrictions updated successfully.',
        ]);
    }

    public function resetDemographics()
    {
        $userId = Auth::id();
        $adminId = 1;

        if ($userId === $adminId) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot reset default template restrictions.',
            ], 403);
        }

        DB::transaction(function () use ($userId, $adminId) {
            DemographicRestriction::where('user_id', $userId)->delete();

            $defaults = DemographicRestriction::where('user_id', $adminId)->get();

            foreach ($defaults as $row) {
                $insert = $row->replicate();
                $insert->user_id = $userId;
                $insert->save();
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Demographic restrictions reset to default successfully.',
            'restored' => true,
        ]);
    }

    public function resetSkills()
    {
        $userId = Auth::id();
        $adminId = 1;

        if ($userId === $adminId) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot reset default template restrictions.',
            ], 403);
        }

        DB::transaction(function () use ($userId, $adminId) {
            SkillRestriction::where('user_id', $userId)->delete();

            $defaults = SkillRestriction::where('user_id', $adminId)->get();

            foreach ($defaults as $row) {
                $insert = $row->replicate();
                $insert->user_id = $userId;
                $insert->save();
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Skill restrictions reset to default successfully.',
            'restored' => true,
        ]);
    }
}
