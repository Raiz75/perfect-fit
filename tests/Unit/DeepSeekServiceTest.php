<?php

namespace Tests\Unit;

use App\Services\DeepSeekService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DeepSeekServiceTest extends TestCase
{
    public function test_interpret_returns_parsed_json_on_success(): void
    {
        Http::fake([
            'api.deepseek.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'tierAdvice' => ['Best fit advice', 'Second tier advice'],
                                'growthOpportunities' => 'Grow here',
                                'ministryPathway' => 'Pathway text',
                            ]),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $service = app(DeepSeekService::class);
        $result = $service->interpret(
            [
                ['ministry' => 'Music Ministry', 'score' => 25, 'rank' => 1],
                ['ministry' => 'Tech Ministry', 'score' => 20, 'rank' => 2],
            ],
            [
                ['titles' => ['Music Ministry'], 'score' => 25],
                ['titles' => ['Tech Ministry'], 'score' => 20],
            ]
        );

        $this->assertEquals('Best fit advice', $result['tierAdvice'][0]);
        $this->assertEquals('Grow here', $result['growthOpportunities']);
    }

    public function test_interpret_throws_on_api_failure(): void
    {
        Http::fake([
            'api.deepseek.com/*' => Http::response(null, 500),
        ]);

        $this->expectException(\RuntimeException::class);

        $service = app(DeepSeekService::class);
        $service->interpret(
            [['ministry' => 'Test', 'score' => 10, 'rank' => 1]],
            [['titles' => ['Test'], 'score' => 10]]
        );
    }

    public function test_interpret_throws_on_invalid_json(): void
    {
        Http::fake([
            'api.deepseek.com/*' => Http::response([
                'choices' => [['message' => ['content' => 'not json at all']]],
            ], 200),
        ]);

        $this->expectException(\RuntimeException::class);

        $service = app(DeepSeekService::class);
        $service->interpret(
            [['ministry' => 'Test', 'score' => 10, 'rank' => 1]],
            [['titles' => ['Test'], 'score' => 10]]
        );
    }
}
