<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\WritingSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WritingSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');

        $query = WritingSubmission::with(['exam', 'examQuestion', 'user'])
            ->orderBy('created_at', 'desc');

        if ($status === 'graded') {
            $query->where('status', 'graded');
        } else {
            $query->where('status', 'pending');
        }

        $submissions = $query->paginate(20)->appends($request->query());

        return view('instructor.writing-submissions.index', compact('submissions', 'status'));
    }

    public function update(Request $request, WritingSubmission $writingSubmission)
    {
        $data = $request->validate([
            'manual_score' => 'required|numeric|min:0|max:100',
            'manual_feedback' => 'nullable|string|max:2000',
        ]);

        $writingSubmission->update([
            'manual_score' => $data['manual_score'],
            'manual_feedback' => $data['manual_feedback'] ?? null,
            'status' => 'graded',
            'graded_by' => Auth::id(),
            'graded_at' => now(),
        ]);

        return redirect()->route('instructor.writing.submissions.index')
            ->with('success', 'Cập nhật điểm chấm thủ công thành công.');
    }
}
