<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Support\Facades\Auth;

class LearningResultController extends Controller
{
    private function normalizedScore(float $score): float
    {
        return round(max(0, min(100, $score)), 1);
    }

    public function index()
    {
        $userId = Auth::id();
        $results = Result::with('exam')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Fetch all results for stats calculation (without pagination)
        $allResults = Result::with('exam')
            ->where('user_id', $userId)
            ->get();

        $readingResults = $allResults->filter(fn($r) => ($r->exam->skill ?? null) === 'reading' && $r->status === 'graded');
        $listeningResults = $allResults->filter(fn($r) => ($r->exam->skill ?? null) === 'listening' && $r->status === 'graded');
        $writingResults = $allResults->filter(fn($r) => ($r->exam->skill ?? null) === 'writing' && $r->status === 'graded');
        $speakingResults = $allResults->filter(fn($r) => ($r->exam->skill ?? null) === 'speaking' && $r->status === 'graded');

        $skillsInfo = [
            'reading' => [
                'avg' => $readingResults->count() > 0 ? round($readingResults->avg('score'), 1) : null,
                'count' => $readingResults->count(),
                'label' => 'Reading',
                'icon' => 'fa-book-open',
                'color' => '#3b82f6',
            ],
            'listening' => [
                'avg' => $listeningResults->count() > 0 ? round($listeningResults->avg('score'), 1) : null,
                'count' => $listeningResults->count(),
                'label' => 'Listening',
                'icon' => 'fa-headphones',
                'color' => '#10b981',
            ],
            'writing' => [
                'avg' => $writingResults->count() > 0 ? round($writingResults->avg('score'), 1) : null,
                'count' => $writingResults->count(),
                'label' => 'Writing',
                'icon' => 'fa-pen-nib',
                'color' => '#f59e0b',
            ],
            'speaking' => [
                'avg' => $speakingResults->count() > 0 ? round($speakingResults->avg('score'), 1) : null,
                'count' => $speakingResults->count(),
                'label' => 'Speaking',
                'icon' => 'fa-microphone',
                'color' => '#ec4899',
            ],
        ];

        // Determine strengths and weaknesses
        $strengths = [];
        $weaknesses = [];

        foreach ($skillsInfo as $key => $info) {
            if ($info['avg'] !== null) {
                $score = $this->normalizedScore((float) $info['avg']);

                if ($score >= 75) {
                    $strengths[] = $info['label'];
                } elseif ($score < 60) {
                    $weaknesses[] = $info['label'];
                }
            }
        }

        // Fallbacks if nothing fits the hard thresholds, but user has done tests
        if (empty($strengths) && empty($weaknesses) && $allResults->where('status', 'graded')->count() > 0) {
            // Find highest and lowest normalized scores
            $normalizedScores = [];
            foreach ($skillsInfo as $key => $info) {
                if ($info['avg'] !== null) {
                    $normalizedScores[$info['label']] = $this->normalizedScore((float) $info['avg']);
                }
            }

            if (!empty($normalizedScores)) {
                arsort($normalizedScores);
                $keys = array_keys($normalizedScores);
                $strengths[] = $keys[0];
                if (count($keys) > 1) {
                    $weaknesses[] = end($keys);
                }
            }
        }

        $totalTests = $allResults->count();

        return view('learning-results.index', compact('results', 'skillsInfo', 'strengths', 'weaknesses', 'totalTests'));
    }

    public function show(Result $result)
    {
        if ($result->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem kết quả này.');
        }

        $result->load(['exam.questions', 'submissions.examQuestion']);

        return view('learning-results.review', compact('result'));
    }
}
