<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GenerateReportRequest;
use App\Models\UserReport;
use App\Services\ChartImageService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    const GENDER_MAP = [1 => 'Male', 2 => 'Female'];

    const MARITAL_MAP = [1 => 'Single', 2 => 'Married'];

    const BAPTIZED_MAP = [1 => 'Yes', 2 => 'No'];

    const FAITH_MAP = [1 => '1+ Week', 2 => '6+ Months', 3 => '1+ Year', 4 => '2+ Years'];

    const SKILL_ORDER = ['music', 'technology', 'writing', 'technical', 'speaking', 'accounting', 'mentoring', 'bible_knowledge'];

    const SKILL_LABELS = ['Music', 'Technology', 'Writing', 'Technical', 'Speaking', 'Accounting', 'Mentoring', 'Bible Knowledge'];

    const AGE_BUCKETS = ['Under 18', '18-25', '26-35', '36-50', '51+'];

    const FAITH_ORDER = ['1+ Week', '6+ Months', '1+ Year', '2+ Years'];

    public function generate(GenerateReportRequest $request, ChartImageService $chartService)
    {
        $user = Auth::user();
        $query = UserReport::where('church_code', $user->church_code);

        // Apply filters (same as DashboardController::getData)
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
                if (in_array($skill, self::SKILL_ORDER)) {
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

        $reports = $query->orderBy('time_of_submission', 'desc')->get();
        $totalTakers = $reports->count();

        // Build table rows
        $tableRows = $this->buildTableRows($reports);

        // Build filters list
        $filters = $this->buildFiltersList($request);

        // Compute chart data (always includes all 7 sections, even if empty)
        $chartData = $this->computeChartData($reports);

        // Generate chart images (only for non-empty charts)
        $chartImages = $this->generateChartImages($chartData, $chartService);

        // Render PDF view
        $pdf = Pdf::loadView('admin.reports.dashboard-pdf', [
            'churchName' => $user->church_name ?? 'PERFIT',
            'adminEmail' => $user->email,
            'generatedAt' => now()->format('Y-m-d H:i'),
            'startDate' => $request->input('startDate'),
            'endDate' => $request->input('endDate'),
            'filters' => $filters,
            'totalTakers' => $totalTakers,
            'chartImages' => $chartImages,
            'chartData' => $chartData,
            'tableRows' => $tableRows,
        ]);

        // Clean up temp chart images AFTER loading view but BEFORE returning download
        $chartService->cleanup();

        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $user->church_name ?? 'PERFIT') . '_Dashboard_Report.pdf';

        return $pdf->download($filename);
    }

    private function buildFiltersList(GenerateReportRequest $request): array
    {
        return [
            ['title' => 'Name or Email', 'value' => $request->input('search', 'N/A')],
            ['title' => 'Age', 'value' => $request->input('age', 'N/A')],
            ['title' => 'Gender', 'value' => self::GENDER_MAP[$request->input('gender')] ?? 'N/A'],
            ['title' => 'Marital Status', 'value' => self::MARITAL_MAP[$request->input('marital')] ?? 'N/A'],
            ['title' => 'Baptized Status', 'value' => self::BAPTIZED_MAP[$request->input('baptized')] ?? 'N/A'],
            ['title' => 'Time in Faith', 'value' => self::FAITH_MAP[$request->input('faith')] ?? 'N/A'],
            ['title' => 'Skills', 'value' => $request->input('skills') ? implode(', ', explode(',', $request->input('skills'))) : 'N/A'],
            ['title' => 'Ministries', 'value' => $request->input('ministries', 'N/A')],
        ];
    }

    private function buildTableRows($reports): array
    {
        return $reports->map(function ($r) {
            $skills = [];
            foreach (self::SKILL_ORDER as $i => $col) {
                if ($r->{$col} == 1) {
                    $skills[] = self::SKILL_LABELS[$i];
                }
            }

            $ministries = [];
            if ($r->eligible_ministry) {
                $ministries = array_filter(array_map('trim', explode(',', $r->eligible_ministry)));
            }

            return [
                'name' => $r->name,
                'email' => $r->email,
                'skills' => $skills,
                'eligible_ministries' => $ministries,
                'gender' => self::GENDER_MAP[$r->gender] ?? '—',
                'age' => $r->age ?? '—',
                'marital' => self::MARITAL_MAP[$r->marital_status] ?? '—',
                'baptized' => self::BAPTIZED_MAP[$r->baptized] ?? '—',
                'faith' => self::FAITH_MAP[$r->time_in_faith] ?? '—',
                'date' => $r->time_of_submission,
            ];
        })->toArray();
    }

    private function computeChartData($reports): array
    {
        $ageBuckets = array_fill_keys(self::AGE_BUCKETS, 0);
        $faithBuckets = array_fill_keys(self::FAITH_ORDER, 0);
        $skillBuckets = array_fill_keys(self::SKILL_ORDER, 0);

        $gender = [];
        $marital = [];
        $baptized = [];
        $ministry = [];
        $age = $ageBuckets;
        $faith = $faithBuckets;
        $skills = $skillBuckets;

        foreach ($reports as $r) {
            // Gender
            $gLabel = self::GENDER_MAP[$r->gender] ?? null;
            if ($gLabel) {
                $gender[$gLabel] = ($gender[$gLabel] ?? 0) + 1;
            }

            // Marital
            $mLabel = self::MARITAL_MAP[$r->marital_status] ?? null;
            if ($mLabel) {
                $marital[$mLabel] = ($marital[$mLabel] ?? 0) + 1;
            }

            // Baptized
            $bLabel = self::BAPTIZED_MAP[$r->baptized] ?? null;
            if ($bLabel) {
                $baptized[$bLabel] = ($baptized[$bLabel] ?? 0) + 1;
            }

            // Faith (only count known labels)
            $fLabel = self::FAITH_MAP[$r->time_in_faith] ?? null;
            if ($fLabel && isset($faith[$fLabel])) {
                $faith[$fLabel]++;
            }

            // Age
            if ($r->age) {
                $bucket = $this->bucketAge($r->age);
                if (isset($age[$bucket])) {
                    $age[$bucket]++;
                }
            }

            // Skills
            foreach (self::SKILL_ORDER as $col) {
                if ($r->{$col} == 1) {
                    $skills[$col]++;
                }
            }

            // Ministry
            if ($r->eligible_ministry) {
                foreach (explode(',', $r->eligible_ministry) as $min) {
                    $m = trim($min);
                    if ($m) {
                        $ministry[$m] = ($ministry[$m] ?? 0) + 1;
                    }
                }
            }
        }

        return [
            'gender' => $gender,
            'age' => $age,
            'baptized' => $baptized,
            'faith' => $faith,
            'skills' => $skills,
            'ministry' => $ministry,
            'marital' => $marital,
        ];
    }

    private function generateChartImages(array $chartData, ChartImageService $chartService): array
    {
        $chartImages = [];

        // Gender — pie
        if (!empty($chartData['gender']) && array_sum($chartData['gender']) > 0) {
            $chartImages['gender'] = $chartService->renderPie(
                array_keys($chartData['gender']),
                array_values($chartData['gender']),
                ['#E6194B', '#F58231'],
                'Gender Distribution'
            );
        }

        // Age — pie
        $ageData = $this->orderedValues($chartData['age'], self::AGE_BUCKETS);
        if (array_sum($ageData) > 0) {
            $chartImages['age'] = $chartService->renderPie(
                self::AGE_BUCKETS,
                $ageData,
                ['#9A6324', '#FFD8B1', '#808000', '#AAFFC3', '#FF7F50'],
                'Age Groups'
            );
        }

        // Baptized — doughnut
        if (!empty($chartData['baptized']) && array_sum($chartData['baptized']) > 0) {
            $chartImages['baptized'] = $chartService->renderDoughnut(
                array_keys($chartData['baptized']),
                array_values($chartData['baptized']),
                ['#3CB44B', '#808080'],
                'Baptized Status'
            );
        }

        // Faith — bar
        $faithData = $this->orderedValues($chartData['faith'], self::FAITH_ORDER);
        if (array_sum($faithData) > 0) {
            $chartImages['faith'] = $chartService->renderBar(
                self::FAITH_ORDER,
                $faithData,
                ['#FFE119', '#BFEF45', '#3CB44B', '#46F0F0'],
                'Time in Faith'
            );
        }

        // Skills — bar
        $skillData = $this->orderedValues($chartData['skills'], self::SKILL_ORDER);
        if (array_sum($skillData) > 0) {
            $chartImages['skills'] = $chartService->renderBar(
                self::SKILL_LABELS,
                $skillData,
                ['#E6194B', '#F58231', '#FFE119', '#BFEF45', '#3CB44B', '#46F0F0', '#4363D8', '#911EB4'],
                'Skills Breakdown'
            );
        }

        // Ministry — bar with HSL-distributed colors
        if (!empty($chartData['ministry']) && array_sum($chartData['ministry']) > 0) {
            $labels = array_keys($chartData['ministry']);
            $chartImages['ministry'] = $chartService->renderBar(
                $labels,
                array_values($chartData['ministry']),
                $this->hslColors(count($labels)),
                'Ministry Eligibility'
            );
        }

        // Marital — bar
        if (!empty($chartData['marital']) && array_sum($chartData['marital']) > 0) {
            $chartImages['marital'] = $chartService->renderBar(
                array_keys($chartData['marital']),
                array_values($chartData['marital']),
                ['#800000', '#808080'],
                'Marital Status'
            );
        }

        return $chartImages;
    }

    private function bucketAge($ageRaw): string
    {
        $n = (int) $ageRaw;
        if ($n < 18) {
            return 'Under 18';
        }
        if ($n <= 25) {
            return '18-25';
        }
        if ($n <= 35) {
            return '26-35';
        }
        if ($n <= 50) {
            return '36-50';
        }

        return '51+';
    }

    private function orderedValues(array $data, array $order): array
    {
        $result = [];
        foreach ($order as $key) {
            $result[] = $data[$key] ?? 0;
        }

        return $result;
    }

    private function hslToHex(float $h, int $s, int $l): string
    {
        $h /= 360;
        $s /= 100;
        $l /= 100;
        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod($h * 6, 2) - 1));
        $m = $l - $c / 2;
        $rgb = match (floor($h * 6)) {
            0 => [$c, $x, 0],
            1 => [$x, $c, 0],
            2 => [0, $c, $x],
            3 => [0, $x, $c],
            4 => [$x, 0, $c],
            5 => [$c, 0, $x],
            default => [0, 0, 0],
        };

        return sprintf(
            '#%02x%02x%02x',
            round(($rgb[0] + $m) * 255),
            round(($rgb[1] + $m) * 255),
            round(($rgb[2] + $m) * 255)
        );
    }

    private function hslColors(int $count): array
    {
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $this->hslToHex(($i * 360) / $count, 70, 60);
        }

        return $colors;
    }
}