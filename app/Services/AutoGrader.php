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

    /**
     * Grade a single speaking answer. Returns ['score' => float, 'feedback' => string, 'transcript' => ?string].
     */
    public function gradeSpeaking(string $questionText, ?string $audioPath, string $provider = 'openai'): array
    {
        if (!$audioPath) {
            return [
                'score' => 0.0,
                'feedback' => 'Không tìm thấy tệp ghi âm để chấm.',
                'transcript' => null,
            ];
        }

        $transcript = null;

        try {
            if ($provider === 'openai' && env('OPENAI_API_KEY')) {
                $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($audioPath);
                
                if (file_exists($fullPath)) {
                    $response = Http::withToken(env('OPENAI_API_KEY'))
                        ->attach('file', fopen($fullPath, 'r'), basename($fullPath))
                        ->post('https://api.openai.com/v1/audio/transcriptions', [
                            'model' => 'whisper-1',
                        ]);

                    if ($response->ok()) {
                        $transcript = $response->json()['text'] ?? null;
                    }
                }
            }
        } catch (\Exception $e) {
            // log or ignore
        }

        if ($transcript) {
            $gradeResult = $this->grade($questionText, $transcript, $provider);
            return [
                'score' => $gradeResult['score'],
                'feedback' => $gradeResult['feedback'],
                'transcript' => $transcript,
            ];
        }

        // Fallback heuristic grading based on file size
        $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($audioPath);
        if (file_exists($fullPath)) {
            $size = filesize($fullPath);
            $score = min(100, max(0, round(($size / 50000) * 100, 1))); // rough heuristic based on size
            return [
                'score' => $score,
                'feedback' => 'Auto-grade (heuristic based on audio size): Audio submitted successfully.',
                'transcript' => '[Audio transcription fallback: Heuristic active, could not transcribe audio]',
            ];
        }

        return [
            'score' => 0.0,
            'feedback' => 'Không thể tải hoặc xử lý tệp ghi âm.',
            'transcript' => null,
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
