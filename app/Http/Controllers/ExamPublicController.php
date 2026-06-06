<?php

namespace App\Http\Controllers;

use App\Models\Exam;
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
        ]);

        $answers = $data['answers'];

        $questions = $exam->questions()->orderBy('order')->get();

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
