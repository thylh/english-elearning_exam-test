<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    private function normalizedScore(float $score): float
    {
        return round(max(0, min(100, $score)), 1);
    }

    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');
        $type   = $request->input('type', 'all'); // 'all', 'writing', 'speaking'

        $query = Submission::with(['exam', 'examQuestion', 'user'])
            ->orderBy('created_at', 'desc');

        if ($status === 'graded') {
            $query->where('status', 'graded');
        } else {
            $query->where('status', 'pending');
        }

        if (in_array($type, ['writing', 'speaking'])) {
            $query->where('type', $type);
        } else {
            $query->whereIn('type', ['writing', 'speaking']);
        }

        $submissions = $query->paginate(20)->appends($request->query());

        return view('instructor.submissions.index', compact('submissions', 'status', 'type'));
    }

    public function update(Request $request, Submission $submission)
    {
        $data = $request->validate([
            'manual_score'    => 'required|numeric|min:0|max:100',
            'manual_feedback' => 'nullable|string|max:2000',
        ]);

        $submission->update([
            'manual_score'    => $this->normalizedScore((float) $data['manual_score']),
            'manual_feedback' => $data['manual_feedback'] ?? null,
            'status'          => 'graded',
            'graded_by'       => Auth::id(),
            'graded_at'       => now(),
        ]);

        if ($submission->result_id) {
            $result = \App\Models\Result::find($submission->result_id);
            if ($result) {
                $subs = $result->submissions()->get();
                $allGraded = !$subs->contains('status', 'pending');
                $sum = $subs->sum(function ($s) {
                    return $s->manual_score ?? $s->auto_score ?? 0;
                });

                $result->update([
                    'score' => $subs->count() > 0 ? round($sum / $subs->count(), 1) : 0,
                    'status' => $allGraded ? 'graded' : 'pending',
                ]);
            }
        }

        return redirect()->route('instructor.submissions.index', $request->only('status', 'type'))
            ->with('success', 'Cập nhật điểm chấm thành công.');
    }
}
