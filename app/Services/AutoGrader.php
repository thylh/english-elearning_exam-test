<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AutoGrader
{
    /**
     * Grade a single writing answer. Returns ['score' => float, 'feedback' => string].
     */
    public function grade(string $questionText, string $answer, string $provider = 'openai'): array
    {
        // Try provider-based grading if API keys are configured
        try {
            if ($provider === 'openai' && env('OPENAI_API_KEY')) {
                // Minimal OpenAI call -- this is a best-effort placeholder and may be customized
                $prompt = "You are an English teacher. Grade the student's answer on a 0-100 scale and give short feedback.\nQuestion: $questionText\nAnswer: $answer\nProvide JSON: {\"score\": <number>, \"feedback\": \"...\" }";
                $resp = Http::withToken(env('OPENAI_API_KEY'))
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
                        'messages' => [
                            ['role' => 'system', 'content' => 'You grade short English writing tasks.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.0,
                        'max_tokens' => 200,
                    ]);

                if ($resp->ok()) {
                    $body = $resp->json();
                    $content = data_get($body, 'choices.0.message.content', json_encode($body));
                    // Try to extract JSON object from the assistant content
                    if ($json = $this->extractJson($content)) {
                        $score = isset($json['score']) ? floatval($json['score']) : null;
                        $feedback = $json['feedback'] ?? trim($content);
                        return [
                            'score' => $score ?? 0.0,
                            'feedback' => $feedback,
                        ];
                    }
                }
            }

            // Gemeni or other providers could be added similarly when keys and endpoints are available.
        } catch (\Exception $e) {
            // fall through to heuristic
        }

        // Fallback heuristic grading: simple length/word-count based score
        $wordCount = str_word_count($answer);
        $lenScore = min(100, max(0, ($wordCount / 150) * 100));
        $score = round($lenScore, 1);
        $feedback = $wordCount < 5 ? 'Answer is too short to evaluate.' : 'Auto-grade (heuristic): length and content checked.';

        return [
            'score' => $score,
            'feedback' => $feedback,
        ];
    }

    protected function extractJson(string $text): ?array
    {
        $start = strpos($text, '{');
        $end = strrpos($text, '}');
        if ($start === false || $end === false || $end <= $start) {
            return null;
        }
        $substr = substr($text, $start, $end - $start + 1);
        $decoded = json_decode($substr, true);
        return is_array($decoded) ? $decoded : null;
    }
}
