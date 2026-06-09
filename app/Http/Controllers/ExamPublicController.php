<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\WritingSubmission;
use App\Services\AutoGrader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ExamPublicController extends Controller
{
    public function index(Request $request)
    {
        $exams = Exam::where('published', true)
            ->where('type', 'exam')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ielts.exam', compact('exams'));
    }

    public function take(Request $request, Exam $exam)
    {
        if (! $exam->published) {
            abort(404);
        }

        $questions = $exam->questions()->orderBy('order')->get();
        // If this exam has a skill, render the corresponding question blade (e.g. reading-question)
        $skill = $exam->skill ?? null;
        $allowed = ['reading','listening','writing','speaking'];
        if ($skill && in_array($skill, $allowed, true)) {
            $view = 'ielts.' . $skill . '-question';
            if (view()->exists($view)) {
                return view($view, compact('exam', 'questions'));
            }
        }

        // For non-skill exams (full tests) show the exam listing or fallback to practice view with the single exam
        if ($exam->type === 'exam') {
            return view('ielts.exam', ['exams' => collect([$exam])]);
        }

        return view('ielts.practice', ['examsBySkill' => collect([$exam->skill ?? 'general' => collect([$exam])])]);
    }

    public function submit(Request $request, Exam $exam)
    {
        if (! $exam->published) {
            abort(404);
        }

        $data = $request->validate([
            'answers' => 'required|array',
            'grading_method' => 'nullable|in:auto,manual',
        ]);

        $answers = $data['answers'];
        $gradingMethod = $data['grading_method'] ?? 'manual';

        $questions = $exam->questions()->orderBy('order')->get();

        // Special handling for writing skill exams
        if (($exam->skill ?? null) === 'writing') {
            $details = [];
            $total = $questions->count();
            $sumScore = 0;

            foreach ($questions as $q) {
                $qid = (string) $q->id;
                $submitted = isset($answers[$qid]) ? (string) $answers[$qid] : '';

                // Persist submission
                $submission = WritingSubmission::create([
                    'exam_id' => $exam->id,
                    'question_id' => $q->id,
                    'user_id' => Auth::id(),
                    'answer_text' => $submitted,
                    'grading_method' => $gradingMethod,
                    'status' => $gradingMethod === 'auto' ? 'graded' : 'pending',
                ]);

                $detail = [
                    'question_id' => $q->id,
                    'submitted' => $submitted,
                    'status' => $submission->status,
                ];

                if ($gradingMethod === 'auto') {
                    $grader = new AutoGrader();
                    $res = $grader->grade($q->question_text, $submitted, env('AUTO_GRADER_PROVIDER', 'openai'));
                    $score = isset($res['score']) ? floatval($res['score']) : 0.0;
                    $feedback = $res['feedback'] ?? null;

                    $submission->update([
                        'auto_score' => $score,
                        'auto_feedback' => $feedback,
                        'status' => 'graded',
                        'graded_at' => now(),
                    ]);

                    $detail['auto_score'] = $score;
                    $detail['auto_feedback'] = $feedback;
                    $sumScore += $score;
                }

                $details[] = $detail;
            }

            $score = 0;
            if ($gradingMethod === 'auto' && $total > 0) {
                // average score across questions
                $score = round($sumScore / $total, 1);
            }

            return view('ielts.exam-result', compact('exam', 'total', 'score', 'details'));
        }

        // Non-writing fallback: existing automatic correctness checking
        $total = $questions->count();
        $correct = 0;
        $details = [];

        foreach ($questions as $q) {
            $qid = (string) $q->id;
            $submitted = $answers[$qid] ?? null;

            $expected = $q->correct_answer;

            $isCorrect = false;
            if (is_null($expected)) {
                $isCorrect = false;
            } else {
                // Normalize
                $exp = is_array($expected) ? $expected : preg_split('/\s*,\s*/', (string) $expected);
                $sub = is_array($submitted) ? $submitted : (is_null($submitted) ? [] : preg_split('/\s*,\s*/', (string) $submitted));
                sort($exp);
                sort($sub);
                $isCorrect = $exp === $sub;
            }

            if ($isCorrect) {
                $correct++;
            }

            $details[] = [
                'question_id' => $q->id,
                'correct' => $isCorrect,
                'expected' => $q->correct_answer,
                'submitted' => $submitted,
            ];
        }

        $score = $total ? round(100 * $correct / $total, 1) : 0;

        return view('ielts.exam-result', compact('exam', 'total', 'correct', 'score', 'details'));
    }
}