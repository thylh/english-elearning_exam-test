<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
            'type' => ['nullable', Rule::in(['practice', 'exam'])],
            'subtype' => ['nullable', Rule::in(['single', 'full'])],
            'skill' => ['nullable', Rule::in(['reading', 'listening', 'writing', 'speaking'])],
            'category' => 'nullable|string|max:255',
            'band' => 'nullable|string|max:50',
        ]);

        $type = $data['type'] ?? null;

        if (!$type && ($request->filled('subtype') || $request->filled('skill'))) {
            $type = 'practice';
        }

        if (!$type) {
            throw ValidationException::withMessages([
                'type' => 'Vui lòng chọn loại đề.',
            ]);
        }

        $data['type'] = $type;

        if ($data['type'] === 'practice') {
            if (!$data['subtype']) {
                throw ValidationException::withMessages([
                    'subtype' => 'Vui lòng chọn dạng đề cho Practice.',
                ]);
            }

            if (!$data['skill']) {
                throw ValidationException::withMessages([
                    'skill' => 'Vui lòng chọn kỹ năng cho Practice.',
                ]);
            }
        } else {
            $data['subtype'] = null;
            $data['skill'] = null;
        }

        $exam->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Phân loại đề thành công.',
        ]);
    }

    public function togglePublish(Request $request, Exam $exam)
    {
        // Check conditions before publishing
        $published = $request->input('published', 0);
        $force = $request->boolean('force');

        // If not forcing, validate basics before allowing publish
        if (in_array($published, [1, '1', true], true) && !$force) {
            // Validating before publishing
            if (!$exam->title) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng nhập tiêu đề đề trước khi publish.',
                ], 422);
            }
            
            if (!$exam->description) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng nhập mô tả đề trước khi publish.',
                ], 422);
            }
            
            if (!$exam->type) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng phân loại đề trước khi publish.',
                ], 422);
            }
        }

        // Convert string "0"/"1" to boolean properly
        $published = in_array($published, [1, '1', true], true) ? 1 : 0;
        $exam->update(['published' => $published]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công.',
            'published' => (bool)$published,
        ]);
    }
}
