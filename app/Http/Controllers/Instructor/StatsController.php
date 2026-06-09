<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Result;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        $totalStudents = User::query()
            ->where('role', 'student')
            ->count();

        $totalExams = Exam::count();
        $totalAttempts = Result::count();
        $pendingManualSubmissions = Submission::query()
            ->where('grading_method', 'manual')
            ->where('status', 'pending')
            ->count();

        $overallAverageScore = Result::query()
            ->where('status', 'graded')
            ->whereNotNull('score')
            ->avg('score');

        $skillMeta = [
            'reading' => [
                'label' => 'Reading',
                'icon' => 'fa-book-open',
                'color' => '#2563eb',
                'light' => '#eff6ff',
            ],
            'listening' => [
                'label' => 'Listening',
                'icon' => 'fa-headphones',
                'color' => '#10b981',
                'light' => '#ecfdf5',
            ],
            'writing' => [
                'label' => 'Writing',
                'icon' => 'fa-pen-nib',
                'color' => '#f59e0b',
                'light' => '#fffbeb',
            ],
            'speaking' => [
                'label' => 'Speaking',
                'icon' => 'fa-microphone',
                'color' => '#ec4899',
                'light' => '#fdf2f8',
            ],
        ];

        $skillRows = Result::query()
            ->join('exams', 'results.exam_id', '=', 'exams.id')
            ->where('results.status', 'graded')
            ->whereNotNull('results.score')
            ->whereIn('exams.skill', array_keys($skillMeta))
            ->select(
                'exams.skill',
                DB::raw('COUNT(results.id) as attempts'),
                DB::raw('ROUND(AVG(results.score), 1) as avg_score')
            )
            ->groupBy('exams.skill')
            ->get()
            ->keyBy('skill');

        $skillStats = [];
        foreach ($skillMeta as $key => $meta) {
            $row = $skillRows->get($key);
            $skillStats[$key] = array_merge($meta, [
                'attempts' => $row ? (int) $row->attempts : 0,
                'avg_score' => $row ? (float) $row->avg_score : null,
            ]);
        }

        $topStudents = Result::query()
            ->join('users', 'results.user_id', '=', 'users.id')
            ->where('results.status', 'graded')
            ->whereNotNull('results.score')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COUNT(results.id) as attempts'),
                DB::raw('ROUND(AVG(results.score), 1) as avg_score')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('avg_score')
            ->orderByDesc('attempts')
            ->limit(5)
            ->get();

        $topExams = Exam::query()
            ->join('results', 'exams.id', '=', 'results.exam_id')
            ->select(
                'exams.id',
                'exams.title',
                'exams.skill',
                'exams.type',
                DB::raw('COUNT(results.id) as attempts'),
                DB::raw("ROUND(AVG(CASE WHEN results.status = 'graded' THEN results.score END), 1) as avg_score")
            )
            ->groupBy('exams.id', 'exams.title', 'exams.skill', 'exams.type')
            ->orderByDesc('attempts')
            ->limit(10)
            ->get();

        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $dailyActivity = Result::query()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as activity_date, COUNT(*) as attempts')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('activity_date')
            ->get()
            ->keyBy('activity_date');

        $activityLabels = [];
        $activityData = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $dayKey = $day->format('Y-m-d');

            $activityLabels[] = $day->format('d/m');
            $activityData[] = isset($dailyActivity[$dayKey]) ? (int) $dailyActivity[$dayKey]->attempts : 0;
        }

        return view('instructor.stats.index', [
            'totalStudents' => $totalStudents,
            'totalExams' => $totalExams,
            'totalAttempts' => $totalAttempts,
            'pendingManualSubmissions' => $pendingManualSubmissions,
            'overallAverageScore' => $overallAverageScore,
            'skillStats' => $skillStats,
            'topStudents' => $topStudents,
            'topExams' => $topExams,
            'activityLabels' => $activityLabels,
            'activityData' => $activityData,
        ]);
    }
}
