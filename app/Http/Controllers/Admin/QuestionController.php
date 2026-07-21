<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BehavioralQuestion;
use App\Models\InterestAndPassionQuestion;
use App\Models\SkillQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function skill()
    {
        $questions = SkillQuestion::with('skill')
            ->where('user_id', Auth::id())
            ->orderBy('skill_id')
            ->orderBy('question_number')
            ->get()
            ->groupBy(fn($q) => $q->skill->name);

        return view('admin.questions.skill', compact('questions'));
    }

    public function interest()
    {
        $questions = InterestAndPassionQuestion::with('ministryCategory')
            ->where('user_id', Auth::id())
            ->orderBy('ministry_category_id')
            ->orderBy('question_number')
            ->get()
            ->groupBy(fn($q) => $q->ministryCategory->name);

        return view('admin.questions.interest', compact('questions'));
    }

    public function behavioral()
    {
        $questions = BehavioralQuestion::with('ministry.category')
            ->where('user_id', Auth::id())
            ->orderBy('ministry_id')
            ->orderBy('question_number')
            ->get()
            ->groupBy(fn($q) => $q->ministry->name);

        return view('admin.questions.behavioral', compact('questions'));
    }

    public function updateSkill(Request $request)
    {
        return $this->updateQuestions($request, SkillQuestion::class);
    }

    public function updateInterest(Request $request)
    {
        return $this->updateQuestions($request, InterestAndPassionQuestion::class);
    }

    public function updateBehavioral(Request $request)
    {
        return $this->updateQuestions($request, BehavioralQuestion::class);
    }

    public function resetSkill()
    {
        return $this->resetQuestions(SkillQuestion::class, 'skill_id');
    }

    public function resetInterest()
    {
        return $this->resetQuestions(InterestAndPassionQuestion::class, 'ministry_category_id');
    }

    public function resetBehavioral()
    {
        return $this->resetQuestions(BehavioralQuestion::class, 'ministry_id');
    }

    private function updateQuestions(Request $request, string $model)
    {
        $data = $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|integer',
            'questions.*.question_en' => 'required|string',
            'questions.*.question_tl' => 'required|string',
        ]);

        $userId = Auth::id();

        DB::transaction(function () use ($data, $model, $userId) {
            foreach ($data['questions'] as $item) {
                $model::where('id', $item['id'])
                    ->where('user_id', $userId)
                    ->update([
                        'question_en' => $item['question_en'],
                        'question_tl' => $item['question_tl'],
                    ]);
            }
        });

        $modelName = class_basename($model);
        $label = match ($modelName) {
            'SkillQuestion' => 'Skill',
            'InterestAndPassionQuestion' => 'Interest & Passion',
            'BehavioralQuestion' => 'Behavioral',
            default => 'Question',
        };

        return response()->json([
            'success' => true,
            'message' => "{$label} questions updated successfully.",
        ]);
    }

    private function resetQuestions(string $model, string $groupColumn)
    {
        $userId = Auth::id();
        $adminId = 1;

        if ($userId === $adminId) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot reset default template questions.',
            ], 403);
        }

        DB::transaction(function () use ($model, $userId, $adminId, $groupColumn) {
            $model::where('user_id', $userId)->delete();

            $defaults = $model::where('user_id', $adminId)->get();

            foreach ($defaults as $row) {
                $insert = $row->replicate();
                $insert->user_id = $userId;
                $insert->save();
            }
        });

        $fresh = $model::where('user_id', $userId)
            ->orderBy($groupColumn)
            ->orderBy('question_number')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Questions reset to default successfully.',
            'questions' => $fresh,
        ]);
    }
}
