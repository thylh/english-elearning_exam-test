<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ExamQuestionController extends Controller
{
    public function index(Exam $exam)
    {
        $questions = $exam->questions()
            ->orderBy('section_skill')
            ->orderBy('part_number')
            ->orderBy('order')
            ->get();

        return view('instructor.exams.questions.index', compact('exam', 'questions'));
    }

    public function store(Request $request, Exam $exam)
    {
        $data = $this->validatedData($request, $exam);
        $data['options'] = $this->normalizeOptions($request->input('options_text'));
        $data['order'] = $data['order'] ?? ((int) $exam->questions()
            ->where('section_skill', $data['section_skill'])
            ->where('part_number', $data['part_number'])
            ->max('order') + 1);

        $exam->questions()->create($data);

        return redirect()
            ->route('instructor.exams.questions.index', $exam)
            ->with('success', 'Đã thêm câu hỏi.');
    }

    public function update(Request $request, Exam $exam, ExamQuestion $question)
    {
        abort_unless($question->exam_id === $exam->id, 404);

        $data = $this->validatedData($request, $exam);
        $data['options'] = $this->normalizeOptions($request->input('options_text'));

        $question->update($data);

        return redirect()
            ->route('instructor.exams.questions.index', $exam)
            ->with('success', 'Đã cập nhật câu hỏi.');
    }

    public function destroy(Exam $exam, ExamQuestion $question)
    {
        abort_unless($question->exam_id === $exam->id, 404);

        $question->delete();

        return redirect()
            ->route('instructor.exams.questions.index', $exam)
            ->with('success', 'Đã xóa câu hỏi.');
    }

    private function validatedData(Request $request, Exam $exam): array
    {
        if (empty($exam->type)) {
            throw ValidationException::withMessages([
                'type' => 'Vui lòng phân loại đề trước khi thêm câu hỏi.',
            ]);
        }

        $data = $request->validate([
            'section_skill' => ['nullable', Rule::in(['reading', 'listening', 'writing', 'speaking'])],
            'part_number' => 'nullable|integer|min:1|max:4',
            'question_text' => 'required|string',
            'question_type' => ['required', Rule::in(['multiple_choice', 'checkbox', 'text', 'writing', 'speaking'])],
            'correct_answer' => 'nullable|string',
            'explanation' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($exam->type === 'practice') {
            $data['section_skill'] = $exam->skill;

            if ($exam->subtype === 'single') {
                $data['part_number'] = 1;
            }
        }

        if ($exam->type === 'exam' && empty($data['section_skill'])) {
            throw ValidationException::withMessages([
                'section_skill' => 'Vui lòng chọn kỹ năng/section cho câu hỏi.',
            ]);
        }

        if (in_array($data['section_skill'] ?? null, ['reading', 'listening'], true) && empty($data['part_number'])) {
            throw ValidationException::withMessages([
                'part_number' => 'Vui lòng chọn part cho Reading/Listening.',
            ]);
        }

        if (!in_array($data['section_skill'] ?? null, ['reading', 'listening'], true)) {
            $data['part_number'] = $data['part_number'] ?: null;
        }

        return $data;
    }

    private function normalizeOptions(?string $optionsText): ?array
    {
        if (!$optionsText) {
            return null;
        }

        $options = collect(preg_split('/\r\n|\r|\n/', $optionsText))
            ->map(fn ($option) => trim($option))
            ->filter()
            ->values()
            ->all();

        return $options ?: null;
    }
}
