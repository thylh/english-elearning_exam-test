<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Submission;
use App\Services\AutoGrader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ExamPublicController extends Controller
{
    private function toPercentScore(?float $score, float $maxScore = 100.0): ?float
    {
        if ($score === null) {
            return null;
        }

        if ($maxScore <= 0) {
            return round(max(0, $score), 1);
        }

        $clamped = min($maxScore, max(0, $score));

        return round(($clamped / $maxScore) * 100, 1);
    }

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

        $origin = $request->query('origin', 'practice');
        $returnTarget = $origin === 'exam-test' ? 'exam-test' : 'practice';
        $returnUrl = $returnTarget === 'exam-test' ? route('exam.index') : url('/practice');

        $questions = $exam->questions()->orderBy('order')->get();
        // If this exam has a skill, render the corresponding question blade (e.g. reading-question)
        $skill = $exam->skill ?? null;
        $allowed = ['reading','listening','writing','speaking'];
        if ($skill && in_array($skill, $allowed, true)) {
            $view = 'ielts.' . $skill . '-question';
            if (view()->exists($view)) {
                return view($view, compact('exam', 'questions', 'returnTarget', 'returnUrl'));
            }
        }

        // For full exams without an explicit skill, render the first section's question page if available.
        if ($exam->type === 'exam' && $questions->isNotEmpty()) {
            $firstSectionSkill = $questions->first()->section_skill;
            if ($firstSectionSkill && in_array($firstSectionSkill, $allowed, true)) {
                $view = 'ielts.' . $firstSectionSkill . '-question';
                if (view()->exists($view)) {
                    return view($view, compact('exam', 'questions', 'returnTarget', 'returnUrl'));
                }
            }

            return view('ielts.exam', ['exams' => collect([$exam]), 'returnTarget' => $returnTarget, 'returnUrl' => $returnUrl]);
        }

        return view('ielts.practice', [
            'examsBySkill' => collect([$exam->skill ?? 'general' => collect([$exam])]),
            'returnTarget' => $returnTarget,
            'returnUrl' => $returnUrl,
        ]);
    }

    public function submit(Request $request, Exam $exam)
    {
        if (! $exam->published) {
            abort(404);
        }

        $data = $request->validate([
            'answers' => 'required|array',
            'grading_method' => 'nullable|in:auto,manual',
            'origin' => 'nullable|in:practice,exam-test',
        ]);

        $answers = $data['answers'];
        $gradingMethod = $data['grading_method'] ?? 'manual';
        $origin = $data['origin'] ?? 'practice';
        $returnTarget = $origin === 'exam-test' ? 'exam-test' : 'practice';
        $returnUrl = $returnTarget === 'exam-test' ? route('exam.index') : url('/practice');
        $returnLabel = $returnTarget === 'exam-test' ? 'Quay lại exam' : 'Quay lại Practice';

        $questions = $exam->questions()->orderBy('order')->get();

        // Special handling for speaking skill exams
        if (($exam->skill ?? null) === 'speaking') {
            $details = [];
            $total = $questions->count();
            $sumScore = 0;

            $result = null;
            if (Auth::check()) {
                $result = \App\Models\Result::create([
                    'user_id' => Auth::id(),
                    'exam_id' => $exam->id,
                    'score' => null,
                    'correct_answers' => null,
                    'total_questions' => $total,
                    'status' => $gradingMethod === 'auto' ? 'graded' : 'pending',
                ]);
            }

            foreach ($questions as $q) {
                $qid = (string) $q->id;
                $submitted = isset($answers[$qid]) ? (string) $answers[$qid] : '';

                // Decode base64 audio and save it
                $audioPath = null;
                if ($submitted && strpos($submitted, 'data:audio/') === 0) {
                    $semi = strpos($submitted, ';');
                    if ($semi !== false) {
                        $mime = substr($submitted, 5, $semi - 5);
                        $ext = 'webm'; // default fallback
                        if (strpos($mime, 'audio/wav') !== false) {
                            $ext = 'wav';
                        } elseif (strpos($mime, 'audio/mpeg') !== false) {
                            $ext = 'mp3';
                        } elseif (strpos($mime, 'audio/webm') !== false) {
                            $ext = 'webm';
                        } elseif (strpos($mime, 'audio/ogg') !== false) {
                            $ext = 'ogg';
                        }

                        $data = substr($submitted, strpos($submitted, 'base64,') + 7);
                        $decoded = base64_decode($data);
                        $filename = 'speaking_' . time() . '_' . uniqid() . '.' . $ext;
                        $path = 'speaking_submissions/' . $filename;
                        \Illuminate\Support\Facades\Storage::disk('public')->put($path, $decoded);
                        $audioPath = $path;
                    }
                }

                // Persist submission
                $submission = Submission::create([
                    'exam_id'        => $exam->id,
                    'question_id'    => $q->id,
                    'user_id'        => Auth::id(),
                    'result_id'      => $result ? $result->id : null,
                    'type'           => 'speaking',
                    'audio_path'     => $audioPath,
                    'grading_method' => $gradingMethod,
                    'status'         => $gradingMethod === 'auto' ? 'graded' : 'pending',
                ]);

                $detail = [
                    'question_id' => $q->id,
                    'question_text' => $q->question_text,
                    'audio_path' => $audioPath,
                    'status' => $submission->status,
                ];

                if ($gradingMethod === 'auto') {
                    $grader = new AutoGrader();
                    $res = $grader->gradeSpeaking($q->question_text, $audioPath, env('AUTO_GRADER_PROVIDER', 'openai'));
                    $rawScore = isset($res['score']) ? floatval($res['score']) : 0.0;
                    $score = $this->toPercentScore($rawScore, 9.0) ?? 0.0;
                    $feedback = $res['feedback'] ?? null;
                    $transcript = $res['transcript'] ?? null;

                    $submission->update([
                        'auto_score' => $score,
                        'auto_feedback' => $feedback,
                        'answer_text' => $transcript,
                        'status' => 'graded',
                        'graded_at' => now(),
                    ]);

                    $detail['auto_score'] = $score;
                    $detail['auto_feedback'] = $feedback;
                    $detail['transcript'] = $transcript;
                    $sumScore += $score;
                }

                $details[] = $detail;
            }

            $score = 0;
            if ($gradingMethod === 'auto' && $total > 0) {
                $score = round($sumScore / $total, 1);
            }

            if ($result) {
                $result->update([
                    'score' => $gradingMethod === 'auto' ? $score : null,
                ]);
            }

            return view('ielts.exam-result', compact('exam', 'total', 'score', 'details', 'returnUrl', 'returnLabel'));
        }

        // Special handling for writing skill exams
        if (($exam->skill ?? null) === 'writing') {
            $details = [];
            $total = $questions->count();
            $sumScore = 0;

            $result = null;
            if (Auth::check()) {
                $result = \App\Models\Result::create([
                    'user_id' => Auth::id(),
                    'exam_id' => $exam->id,
                    'score' => null,
                    'correct_answers' => null,
                    'total_questions' => $total,
                    'status' => $gradingMethod === 'auto' ? 'graded' : 'pending',
                ]);
            }

            foreach ($questions as $q) {
                $qid = (string) $q->id;
                $submitted = isset($answers[$qid]) ? (string) $answers[$qid] : '';

                // Persist submission
                $submission = Submission::create([
                    'exam_id'        => $exam->id,
                    'question_id'    => $q->id,
                    'user_id'        => Auth::id(),
                    'result_id'      => $result ? $result->id : null,
                    'type'           => 'writing',
                    'answer_text'    => $submitted,
                    'grading_method' => $gradingMethod,
                    'status'         => $gradingMethod === 'auto' ? 'graded' : 'pending',
                ]);

                $detail = [
                    'question_id' => $q->id,
                    'question_text' => $q->question_text,
                    'submitted' => $submitted,
                    'status' => $submission->status,
                ];

                if ($gradingMethod === 'auto') {
                    $grader = new AutoGrader();
                    $res = $grader->grade($q->question_text, $submitted, env('AUTO_GRADER_PROVIDER', 'openai'));
                    $rawScore = isset($res['score']) ? floatval($res['score']) : 0.0;
                    $score = $this->toPercentScore($rawScore, 9.0) ?? 0.0;
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

            if ($result) {
                $result->update([
                    'score' => $gradingMethod === 'auto' ? $score : null,
                ]);
            }

            return view('ielts.exam-result', compact('exam', 'total', 'score', 'details', 'returnUrl', 'returnLabel'));
        }

        // Non-writing fallback: existing automatic correctness checking
        $total = $questions->count();
        $correct = 0;
        $details = [];

        $result = null;
        if (Auth::check()) {
            $result = \App\Models\Result::create([
                'user_id' => Auth::id(),
                'exam_id' => $exam->id,
                'score' => null,
                'correct_answers' => null,
                'total_questions' => $total,
                'status' => 'graded',
            ]);
        }

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

            if ($result) {
                Submission::create([
                    'exam_id'        => $exam->id,
                    'question_id'    => $q->id,
                    'user_id'        => Auth::id(),
                    'result_id'      => $result->id,
                    'type'           => $exam->skill ?? 'reading',
                    'answer_text'    => is_array($submitted) ? implode(',', $submitted) : (string)$submitted,
                    'grading_method' => 'auto',
                    'auto_score'     => $isCorrect ? 1.0 : 0.0,
                    'status'         => 'graded',
                    'graded_at'      => now(),
                ]);
            }

            $details[] = [
                'question_id' => $q->id,
                'question_text' => $q->question_text,
                'correct' => $isCorrect,
                'expected' => $q->correct_answer,
                'submitted' => $submitted,
            ];
        }

        $score = $total ? round(100 * $correct / $total, 1) : 0;

        if ($result) {
            $result->update([
                'score' => $score,
                'correct_answers' => $correct,
            ]);
        }

        return view('ielts.exam-result', compact('exam', 'total', 'correct', 'score', 'details', 'returnUrl', 'returnLabel'));
    }
}
