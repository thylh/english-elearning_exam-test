@php
    $selectedSkill = old('section_skill', $question?->section_skill ?? $exam->skill);
    $selectedPart = old('part_number', $question?->part_number ?? ($exam->subtype === 'single' ? 1 : null));
    $selectedType = old('question_type', $question?->question_type ?? 'multiple_choice');
    $optionsText = old('options_text', $question ? implode("\n", $question->options ?? []) : '');
@endphp

@if($exam->type === 'exam')
    <div class="mb-3">
        <label class="form-label">Phần kỹ năng</label>
        <select name="section_skill" class="form-control" required>
            <option value="">Chọn phần</option>
            @foreach($skillLabels as $value => $label)
                <option value="{{ $value }}" @selected($selectedSkill === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
@else
    <input type="hidden" name="section_skill" value="{{ $exam->skill }}">
    <div class="mb-3">
        <label class="form-label">Kỹ năng</label>
        <input type="text" class="form-control" value="{{ $skillLabels[$exam->skill] ?? $exam->skill }}" disabled>
    </div>
@endif

<div class="mb-3 part-field">
    <label class="form-label">Part</label>
    <select name="part_number" class="form-control">
        <option value="">Chọn part</option>
        @for($part = 1; $part <= 4; $part++)
            <option value="{{ $part }}" @selected((int) $selectedPart === $part)>Part {{ $part }}</option>
        @endfor
    </select>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">Thứ tự</label>
        <input type="number" name="order" class="form-control" min="0"
            value="{{ old('order', $question?->order) }}">
    </div>
    <div class="col-md-9">
        <label class="form-label">Loại câu hỏi</label>
        <select name="question_type" class="form-control question-type-select">
            @foreach($questionTypes as $value => $label)
                <option value="{{ $value }}" @selected($selectedType === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-3">
    <label class="form-label">Nội dung câu hỏi</label>
    <textarea name="question_text" class="form-control" rows="4" required>{{ old('question_text', $question?->question_text) }}</textarea>
</div>

<div class="mt-3 options-field">
    <label class="form-label">Các lựa chọn</label>
    <textarea name="options_text" class="form-control" rows="4"
        placeholder="Mỗi lựa chọn một dòng">{{ $optionsText }}</textarea>
</div>

<div class="mt-3">
    <label class="form-label">Đáp án đúng / gợi ý đáp án</label>
    <textarea name="correct_answer" class="form-control" rows="2">{{ old('correct_answer', $question?->correct_answer) }}</textarea>
</div>

<div class="my-3">
    <label class="form-label">Giải thích</label>
    <textarea name="explanation" class="form-control" rows="2">{{ old('explanation', $question?->explanation) }}</textarea>
</div>
