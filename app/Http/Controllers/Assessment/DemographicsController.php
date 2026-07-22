<?php

namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assessment\StoreDemographicsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class DemographicsController extends Controller
{
    public function show()
    {
        $phase1 = session('assessment.phase1', []);

        return view('assessment.index', compact('phase1'));
    }

    public function store(StoreDemographicsRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        session(['assessment.phase1' => $validated]);
        session(['assessment.current_phase' => 2]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Personal details saved successfully.',
            ]);
        }

        return redirect()->route('assessment')->with('success', 'Personal details saved successfully.');
    }
}
