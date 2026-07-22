<?php

namespace Tests\Feature;

use App\Http\Controllers\Assessment\AssessmentController;
use App\Models\BehavioralQuestion;
use App\Models\DemographicRestriction;
use App\Models\InterestAndPassionQuestion;
use App\Models\Ministry;
use App\Models\MinistryCategory;
use App\Models\Skill;
use App\Models\SkillQuestion;
use App\Models\SkillRestriction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AssessmentPhase5Test extends TestCase
{
    use RefreshDatabase;

    private function callRankMethod(array $ministries, array $scores): array
    {
        $controller = new AssessmentController;
        $reflection = new \ReflectionMethod($controller, 'rankMinistries');
        $reflection->setAccessible(true);

        return $reflection->invoke($controller, $ministries, $scores);
    }

    public function test_ranking_sorts_by_score_descending(): void
    {
        $ministries = [
            ['name' => 'Music', 'id' => 1],
            ['name' => 'Tech', 'id' => 2],
            ['name' => 'Writing', 'id' => 3],
        ];
        $scores = [1 => 15, 2 => 25, 3 => 10];

        $result = $this->callRankMethod($ministries, $scores);

        $this->assertEquals('Tech', $result[0]['ministry']);
        $this->assertEquals(25, $result[0]['score']);
        $this->assertEquals('Music', $result[1]['ministry']);
        $this->assertEquals('Writing', $result[2]['ministry']);
    }

    public function test_ranking_assigns_same_rank_for_ties(): void
    {
        $ministries = [
            ['name' => 'Music', 'id' => 1],
            ['name' => 'Tech', 'id' => 2],
        ];
        $scores = [1 => 20, 2 => 20];

        $result = $this->callRankMethod($ministries, $scores);

        $this->assertEquals(1, $result[0]['rank']);
        $this->assertEquals(1, $result[1]['rank']);
    }

    public function test_phase5_creates_report_on_completion(): void
    {
        $category = MinistryCategory::create(['id' => 1, 'name' => 'Core']);
        $ministry = Ministry::create(['id' => 1, 'ministry_category_id' => 1, 'name' => 'Test Ministry']);
        $skill = Skill::create(['id' => 1, 'name' => 'Music']);

        User::create(['id' => 1, 'church_code' => 'TESTCODE1', 'name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('pass')]);
        DemographicRestriction::create(['user_id' => 1, 'ministry_id' => 1]);
        SkillRestriction::create(['user_id' => 1, 'ministry_id' => 1]);
        SkillQuestion::create(['user_id' => 1, 'skill_id' => 1, 'question_number' => 1, 'question_en' => 'Test Q', 'question_tl' => 'Test Q']);
        InterestAndPassionQuestion::create(['user_id' => 1, 'ministry_category_id' => 1, 'question_number' => 1, 'question_en' => 'Test Q', 'question_tl' => 'Test Q']);
        $bq = BehavioralQuestion::create(['user_id' => 1, 'ministry_id' => 1, 'question_number' => 1, 'question_en' => 'Test Q', 'question_tl' => 'Test Q']);

        Http::fake([
            'api.deepseek.com/*' => Http::response([
                'choices' => [['message' => ['content' => json_encode([
                    'bestFit' => ['titles' => ['Test Ministry'], 'advice' => 'Great fit!'],
                    'top2' => null,
                    'top3' => null,
                    'otherMinistries' => [],
                    'growthOpportunities' => 'Keep growing',
                    'ministryPathway' => 'Your path',
                ])]]],
            ], 200),
        ]);

        session([
            'assessment.church_code' => 'TESTCODE1',
            'assessment.current_phase' => 5,
            'assessment.phase1' => [
                'name' => 'John Doe', 'email' => 'john@test.com', 'contact' => '09170000000',
                'gender' => '1', 'age' => '25', 'status' => '1', 'baptized' => '1', 'timeInFaith' => '3',
            ],
            'assessment.phase2' => [
                'scores' => [],
                'groupTotals' => [1 => 15],
            ],
            'assessment.phase3' => [
                'scores' => [],
                'groupTotals' => [1 => 20],
            ],
            'assessment.phase4' => [
                'scores' => [$bq->id => 5],
            ],
        ]);

        $this->get(route('assessment.index'));

        $this->assertDatabaseHas('user_reports', [
            'church_code' => 'TESTCODE1',
            'name' => 'John Doe',
            'email' => 'john@test.com',
        ]);
    }

    public function test_phase5_handles_deepseek_failure_gracefully(): void
    {
        $category = MinistryCategory::create(['id' => 1, 'name' => 'Core']);
        $ministry = Ministry::create(['id' => 1, 'ministry_category_id' => 1, 'name' => 'Test Ministry']);
        $skill = Skill::create(['id' => 1, 'name' => 'Music']);

        User::create(['id' => 1, 'church_code' => 'TESTCODE2', 'name' => 'Admin', 'email' => 'admin2@test.com', 'password' => bcrypt('pass')]);
        DemographicRestriction::create(['user_id' => 1, 'ministry_id' => 1]);
        SkillRestriction::create(['user_id' => 1, 'ministry_id' => 1]);
        SkillQuestion::create(['user_id' => 1, 'skill_id' => 1, 'question_number' => 1, 'question_en' => 'Test Q', 'question_tl' => 'Test Q']);
        InterestAndPassionQuestion::create(['user_id' => 1, 'ministry_category_id' => 1, 'question_number' => 1, 'question_en' => 'Test Q', 'question_tl' => 'Test Q']);
        $bq = BehavioralQuestion::create(['user_id' => 1, 'ministry_id' => 1, 'question_number' => 1, 'question_en' => 'Test Q', 'question_tl' => 'Test Q']);

        Http::fake([
            'api.deepseek.com/*' => Http::response(null, 500),
        ]);

        session([
            'assessment.church_code' => 'TESTCODE2',
            'assessment.current_phase' => 5,
            'assessment.phase1' => [
                'name' => 'Jane Doe', 'email' => 'jane@test.com', 'contact' => '09180000000',
                'gender' => '2', 'age' => '30', 'status' => '2', 'baptized' => '1', 'timeInFaith' => '4',
            ],
            'assessment.phase2' => ['scores' => [], 'groupTotals' => [1 => 12]],
            'assessment.phase3' => ['scores' => [], 'groupTotals' => [1 => 18]],
            'assessment.phase4' => ['scores' => [$bq->id => 4]],
        ]);

        $response = $this->get(route('assessment.index'));

        $response->assertStatus(200);
        $response->assertSee('Unable to generate AI interpretation');

        $this->assertDatabaseHas('user_reports', [
            'church_code' => 'TESTCODE2',
            'name' => 'Jane Doe',
        ]);
    }
}
