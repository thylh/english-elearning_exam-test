@php
    // Only restore toolbar selection (skill/part) from old input, not form fields
    $selectedSkill = old('section_skill', $question?->section_skill ?? $exam->skill);
    $selectedPart = old('part_number', $question?->part_number ?? ($exam->subtype === 'single' ? 1 : null));
    $selectedType = $question?->question_type ?? 'multiple_choice';

    $showToolbar = $showToolbar ?? false;
@endphp

@if($exam->type === 'practice' && !$showToolbar)
    <input type="hidden" name="section_skill" value="{{ $selectedSkill }}">
    <input type="hidden" name="part_number" value="{{ $selectedPart }}">
@endif

@if($question)
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Thứ tự</label>
            <input type="number" name="order" class="form-control" min="1" value="{{ old('order', $question?->order) }}">
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
@else
    <div class="mb-3">
        <label class="form-label">Loại câu hỏi</label>
        <select name="question_type" class="form-control question-type-select">
            @foreach($questionTypes as $value => $label)
                <option value="{{ $value }}" @selected($selectedType === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
@endif

<div class="mt-3">
    <label class="form-label">Nội dung câu hỏi</label>
    <textarea name="question_text" class="form-control" rows="4" required>{{ $question?->question_text }}</textarea>
</div>

<div class="mt-3 attachment-field" style="display:none;">
    <label class="form-label">Đính kèm đề bài</label>
    <input type="file" name="prompt_attachment" class="form-control" accept=".jpg,.jpeg,.png,.gif,.mp3,.wav,.mp4,.pdf">
    <div class="form-text attachment-hint"></div>
    @if($question?->prompt_attachment)
        <div class="mt-2">
            <strong>Đính kèm hiện tại:</strong>
            <a href="{{ asset('storage/' . $question->prompt_attachment) }}" target="_blank" rel="noopener">
                {{ basename($question->prompt_attachment) }}
            </a>
        </div>
    @endif
</div>

<div class="mt-3 options-field">
    <label class="form-label">Các lựa chọn</label>
    <textarea name="options_text" class="form-control" rows="4"
        placeholder="Mỗi lựa chọn một dòng">{{ $question ? implode("\n", $question->options ?? []) : '' }}</textarea>
</div>

<div class="mt-3 correct-answer-wrapper">
    <label class="form-label correct-answer-label">Đáp án đúng / gợi ý đáp án</label>
    <textarea name="correct_answer" class="form-control" rows="2">{{ $question?->correct_answer }}</textarea>
    <div class="form-text answer-hint"></div>
</div>

<div class="my-3">
    <label class="form-label">Giải thích</label>
    <textarea name="explanation" class="form-control" rows="2">{{ $question?->explanation }}</textarea>
</div>