<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use Illuminate\Support\Str;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::orderBy('created_at', 'desc')->paginate(20);
        return view('instructor.exams.index', compact('exams'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['user_id'] = Auth::id();

        $exam = Exam::create($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Exam created successfully.',
                'exam' => $exam,
            ]);
        }

        return redirect()->route('instructor.exams.index')
            ->with('success', 'Exam created successfully.');
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['title']);

        $exam->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Exam updated successfully.',
                'exam' => $exam,
            ]);
        }

        return redirect()->route('instructor.exams.index')
            ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('instructor.exams.index')
            ->with('success', 'Exam deleted.');
    }

    public function classify(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'type' => 'required|string|in:practice,exam',
            'subtype' => 'nullable|string|in:single,full',
            'category' => 'nullable|string|max:255',
            'band' => 'nullable|string|max:50',
        ]);

        $exam->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Phân loại đề thành công.',
        ]);
    }

    public function togglePublish(Request $request, Exam $exam)
    {
        $published = $request->input('published', 0);
        $exam->update(['published' => (bool)$published]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công.',
            'published' => (bool)$published,
        ]);
    }
}