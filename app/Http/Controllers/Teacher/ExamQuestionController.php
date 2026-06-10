<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        return view('teacher.exams.questions.index', compact('exam', 'questions'));
    }

    public function store(Request $request, Exam $exam)
    {
        $data = $this->validatedData($request, $exam);
        $data['options'] = $this->normalizeOptions($request->input('options_text'));

        if ($request->hasFile('prompt_attachment')) {
            $data['prompt_attachment'] = $request->file('prompt_attachment')
                ->store('exam_prompts', 'local');
        }

        $data['order'] = $this->resolveInsertedOrder($exam, $data['section_skill'], $data['part_number'], $data['order'] ?? null);

        $exam->questions()->create($data);

        return redirect()
            ->route('teacher.exams.questions.index', $exam)
            ->with('success', 'Đã thêm câu hỏi.')
            ->withInput($request->only('section_skill', 'part_number'));
    }

    public function update(Request $request, Exam $exam, ExamQuestion $question)
    {
        abort_unless($question->exam_id === $exam->id, 404);

        $data = $this->validatedData($request, $exam);
        $data['options'] = $this->normalizeOptions($request->input('options_text'));

        if ($request->hasFile('prompt_attachment')) {
            if ($question->prompt_attachment) {
                Storage::disk('local')->delete($question->prompt_attachment);
            }

            $data['prompt_attachment'] = $request->file('prompt_attachment')
                ->store('exam_prompts', 'local');
        } elseif ($request->boolean('remove_prompt_attachment')) {
            if ($question->prompt_attachment) {
                Storage::disk('local')->delete($question->prompt_attachment);
            }
            $data['prompt_attachment'] = null;
        }

        $data['order'] = $this->resolveUpdatedOrder($question, $data['order'] ?? $question->order, $data['section_skill'], $data['part_number']);

        $question->update($data);

        return redirect()
            ->route('teacher.exams.questions.index', $exam)
            ->with('success', 'Đã cập nhật câu hỏi.')
            ->withInput($request->only('section_skill', 'part_number'));
    }

    public function destroy(Exam $exam, ExamQuestion $question)
    {
        abort_unless($question->exam_id === $exam->id, 404);

        $question->delete();

        return redirect()
            ->route('teacher.exams.questions.index', $exam)
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
            'prompt_attachment' => 'nullable|file|max:51200|mimes:jpg,jpeg,png,gif,mp3,wav,mp4,pdf',
            'remove_prompt_attachment' => 'nullable|boolean',
            'prompt_text' => 'nullable|string',
            'question_type' => ['required', Rule::in(['multiple_choice', 'checkbox', 'text', 'writing', 'speaking'])],
            'correct_answer' => 'nullable|string',
            'explanation' => 'nullable|string',
            'order' => 'nullable|integer|min:1',
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

    private function resolveInsertedOrder(Exam $exam, string $sectionSkill, ?int $partNumber, ?int $requestedOrder): int
    {
        $groupQuery = $exam->questions()
            ->where('section_skill', $sectionSkill)
            ->where('part_number', $partNumber);

        $maxOrder = (int) $groupQuery->max('order');
        $order = $requestedOrder ? max(1, $requestedOrder) : $maxOrder + 1;

        if ($order > $maxOrder + 1) {
            return $maxOrder + 1;
        }

        $groupQuery->where('order', '>=', $order)->increment('order');

        return $order;
    }

    private function resolveUpdatedOrder(ExamQuestion $question, int $requestedOrder, string $newSectionSkill, ?int $newPartNumber): int
    {
        $oldOrder = $question->order;
        $oldSectionSkill = $question->section_skill;
        $oldPartNumber = $question->part_number;
        $newOrder = max(1, $requestedOrder);
        $newGroupQuery = $question->exam->questions()
            ->where('section_skill', $newSectionSkill)
            ->where('part_number', $newPartNumber)
            ->where('id', '!=', $question->id);

        $maxOrder = (int) $newGroupQuery->max('order');

        if ($oldSectionSkill === $newSectionSkill && $oldPartNumber === $newPartNumber) {
            if ($newOrder > $maxOrder + 1) {
                $newOrder = $maxOrder + 1;
            }

            if ($newOrder === $oldOrder) {
                return $newOrder;
            }

            if ($newOrder > $oldOrder) {
                $newGroupQuery = $question->exam->questions()
                    ->where('section_skill', $newSectionSkill)
                    ->where('part_number', $newPartNumber)
                    ->where('order', '>', $oldOrder)
                    ->where('order', '<=', $newOrder)
                    ->where('id', '!=', $question->id);

                $newGroupQuery->decrement('order');
            } else {
                $newGroupQuery = $question->exam->questions()
                    ->where('section_skill', $newSectionSkill)
                    ->where('part_number', $newPartNumber)
                    ->where('order', '>=', $newOrder)
                    ->where('order', '<', $oldOrder)
                    ->where('id', '!=', $question->id);

                $newGroupQuery->increment('order');
            }

            return $newOrder;
        }

        // Move to a different section/part group.
        $question->exam->questions()
            ->where('section_skill', $oldSectionSkill)
            ->where('part_number', $oldPartNumber)
            ->where('order', '>', $oldOrder)
            ->decrement('order');

        if ($newOrder > $maxOrder + 1) {
            $newOrder = $maxOrder + 1;
        }

        $question->exam->questions()
            ->where('section_skill', $newSectionSkill)
            ->where('part_number', $newPartNumber)
            ->where('order', '>=', $newOrder)
            ->increment('order');

        return $newOrder;
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