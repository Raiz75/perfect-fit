<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpClient;
use RuntimeException;

class DeepSeekService
{
    public function __construct(
        private HttpClient $http,
        private ?string $apiKey = '',
        private string $model = 'deepseek-chat',
    ) {}

    public function interpret(array $rankedData, array $tiers, string $language = 'en'): array
    {
        $prompt = $this->buildPrompt($rankedData, $tiers, $language);

        $response = $this->http->withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(60)->post('https://api.deepseek.com/chat/completions', [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a church ministry assessment assistant. Return ONLY valid JSON with no markdown formatting, no code blocks, no extra text.',
                ],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
        ]);

        if ($response->failed()) {
            throw new RuntimeException('DeepSeek API request failed: '.$response->body());
        }

        $body = $response->json();
        $content = $body['choices'][0]['message']['content'] ?? null;

        if (! $content) {
            throw new RuntimeException('Empty response from DeepSeek API');
        }

        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid JSON from DeepSeek API: '.json_last_error_msg());
        }

        return $decoded;
    }

    private function buildPrompt(array $rankedData, array $tiers, string $language): string
    {
        $lang = $language === 'tl' ? 'Tagalog' : 'English';
        $tiersList = '';
        $labels = ['Best Fit', 'Second Tier', 'Third Tier'];
        foreach ($tiers as $i => $tier) {
            $label = $labels[$i] ?? 'Tier '.($i + 1);
            $names = implode(', ', $tier['titles']);
            $tiersList .= "{$label} (Score: {$tier['score']}): {$names}\n";
        }

        $hasLeastAligned = count($tiers) > 3;
        $leastAlignedSection = $hasLeastAligned
            ? '- leastAligned: { titles: string[], advice: string } (lowest scoring ministries)'
            : '';

        return <<<PROMPT
Analyze the following ministry assessment results for a church volunteer. Respond in {$lang}.

Ministry tiers (grouped by score):
{$tiersList}

Return a JSON object with these exact keys:
- tierAdvice: string[] (one advice string per tier, focusing on spiritual growth and calling for that tier's ministries)
- spiritualRelationship: string (how the top ministries complement each other, omit if only 1 ministry total)
- growthOpportunities: string
- ministryPathway: string
{$leastAlignedSection}

Each advice field should be approximately 100 words.
PROMPT;
    }
}
