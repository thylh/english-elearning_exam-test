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

    public function create()
    {
        return view('instructor.exams.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:practice,exam',
            'band' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:0',
            'published' => 'nullable|boolean',
        ]);

        $data['published'] = !empty($data['published']);
        $data['slug'] = Str::slug($data['title']);
        $data['user_id'] = Auth::id();

        Exam::create($data);

        return redirect()->route('instructor.exams.index')
            ->with('success', 'Exam created successfully.');
    }

    public function edit(Exam $exam)
    {
        return view('instructor.exams.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:practice,exam',
            'band' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:0',
            'published' => 'nullable|boolean',
        ]);

        $data['published'] = !empty($data['published']);
        $data['slug'] = Str::slug($data['title']);

        $exam->update($data);

        return redirect()->route('instructor.exams.index')
            ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('instructor.exams.index')
            ->with('success', 'Exam deleted.');
    }
}