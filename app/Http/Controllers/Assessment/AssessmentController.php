<?php

namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assessment\StoreDemographicsRequest;
use App\Models\Skill;
use App\Models\SkillQuestion;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function setChurchCode(Request $request): JsonResponse
    {
        $request->validate(['church_code' => 'required|string|max:9']);

        $exists = User::where('church_code', $request->church_code)->exists();

        if (!$exists) {
            return response()->json(['exists' => false, 'message' => 'Invalid church code.'], 404);
        }

        session(['assessment.church_code' => $request->church_code]);

        return response()->json(['exists' => true]);
    }

    public function show()
    {
        $churchCode = session('assessment.church_code', '');
        $phase1 = session('assessment.phase1', []);
        $currentPhase = session('assessment.current_phase', 1);

        $skillQuestions = collect();
        $skills = collect();
        if ($churchCode && $currentPhase >= 2) {
            $admin = User::where('church_code', $churchCode)->first();
            if ($admin) {
                $skillQuestions = SkillQuestion::where('user_id', $admin->id)
                    ->orderBy('skill_id')
                    ->orderBy('question_number')
                    ->get();
                $skills = Skill::all()->keyBy('id');
            }
        }

        return view('assessment.index', compact('phase1', 'currentPhase', 'skillQuestions', 'skills'));
    }

    public function storePhase1(StoreDemographicsRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['church_code'] = session('assessment.church_code', '');

        session(['assessment.phase1' => $validated]);
        session(['assessment.current_phase' => 2]);

        return redirect()->route('assessment.index');
    }

    public function reset(): RedirectResponse
    {
        session()->forget('assessment');

        return redirect()->route('home');
    }

    public function storePhase2(Request $request): RedirectResponse
    {
        $answers = $request->input('answers', []);

        if (empty($answers)) {
            return redirect()->route('assessment.index')->withErrors(['answers' => 'Please answer all questions.']);
        }

        $churchCode = session('assessment.church_code', '');
        $admin = User::where('church_code', $churchCode)->first();
        $totalQuestions = $admin ? SkillQuestion::where('user_id', $admin->id)->count() : 0;

        if (count($answers) !== $totalQuestions) {
            return redirect()->route('assessment.index')->withErrors(['answers' => 'Please answer all questions before proceeding.']);
        }

        $validated = [];
        foreach ($answers as $questionId => $score) {
            $score = (int) $score;
            if ($score < 1 || $score > 6) {
                return redirect()->route('assessment.index')->withErrors(["answers.$questionId" => 'Invalid score.']);
            }
            $validated[$questionId] = $score;
        }

        $groupTotals = [];
        foreach ($validated as $questionId => $score) {
            $question = SkillQuestion::find($questionId);
            if ($question) {
                $skillId = $question->skill_id;
                if (!isset($groupTotals[$skillId])) {
                    $groupTotals[$skillId] = 0;
                }
                $groupTotals[$skillId] += $score;
            }
        }

        session(['assessment.phase2' => [
            'scores' => $validated,
            'groupTotals' => $groupTotals,
        ]]);
        session(['assessment.current_phase' => 3]);

        return redirect()->route('assessment.index');
    }
}
