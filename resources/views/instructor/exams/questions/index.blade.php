@extends('layouts.app')

@push('head')
    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])
@endpush

@php
    $skillLabels = [
        'reading' => 'Reading',
        'listening' => 'Listening',
        'writing' => 'Writing',
        'speaking' => 'Speaking',
    ];

    $questionTypes = [
        'multiple_choice' => 'Trắc nghiệm 1 đáp án',
        'checkbox' => 'Chọn nhiều đáp án',
        'text' => 'Listening key',
        'writing' => 'Writing response',
        'speaking' => 'Speaking prompt',
    ];

    $isPractice = $exam->type === 'practice';
    $isSinglePractice = $isPractice && $exam->subtype === 'single';
    $needsPart = fn (?string $skill) => in_array($skill, ['reading', 'listening'], true);
@endphp

@section('content')
    @include('partials.dashboard-header')

    <section class="hero">
        <div class="hero-content">
            <span class="hero-tag">QUESTION MANAGEMENT</span>
            {{-- <h1>Nội dung đề thi</h1> --}}
            <p>Đề - {{ $exam->title }}</p>
        </div>
    </section>

    <div class="container my-4">
        @include('partials.alert')

        <div class="d-flex justify-content-between align-items-center gap-3 mb-3 flex-wrap">
            <a href="{{ route('instructor.exams.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Quay lại danh sách đề
            </a>
            <div class="text-muted">{{ $questions->count() }} câu hỏi</div>
        </div>

        {{-- <div class="question-blueprint mb-4">
            <strong>Cấu trúc nội dung:</strong>
            @if($exam->type === 'exam')
                tạo đủ 4 phần Reading, Listening, Writing, Speaking.
            @elseif($exam->subtype === 'full')
                tạo full bài {{ $skillLabels[$exam->skill] ?? $exam->skill }}{{ $needsPart($exam->skill) ? ' gồm Part 1-4.' : '.' }}
            @else
                tạo đề lẻ {{ $skillLabels[$exam->skill] ?? $exam->skill }} - Part 1.
            @endif
        </div> --}}

        {{-- Toolbar: global skill/part selector for exam or full-practice --}}
        <div class="d-flex gap-2 align-items-center mb-2">
                        <strong>Skill:</strong>
                        <span class="badge bg-primary ms-2">{{ $skillLabels[$exam->skill] ?? $exam->skill }}</span>
                    </div>
        @php
            $showToolbar = $exam->type === 'exam' || ($exam->type === 'practice' && $exam->subtype === 'full');
        @endphp

        @if($showToolbar)
            <div class="mb-3">
                @if($exam->type === 'exam')
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <strong>Skill:</strong>
                        @foreach($skillLabels as $value => $label)
                            <button type="button" class="btn btn-outline-primary btn-sm skill-btn" data-skill="{{ $value }}">{{ $label }}</button>
                        @endforeach
                    </div>
                @else
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <strong>Skill:</strong>
                        <span class="badge bg-primary ms-2">{{ $skillLabels[$exam->skill] ?? $exam->skill }}</span>
                    </div>
                @endif

                <div class="d-flex gap-2 align-items-center">
                    <strong>Part:</strong>
                    @for($part = 1; $part <= 4; $part++)
                        <button type="button" class="btn btn-outline-secondary btn-sm part-btn" data-part="{{ $part }}">Part {{ $part }}</button>
                    @endfor
                    <button type="button" class="btn btn-outline-danger btn-sm part-clear-btn">Clear</button>
                </div>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div id="createQuestionPanel" class="question-panel">
                    <h2>Thêm câu hỏi</h2>

                    <form method="POST" action="{{ route('instructor.exams.questions.store', $exam) }}" id="createQuestionForm" class="question-form"
                        enctype="multipart/form-data"
                        data-exam-type="{{ $exam->type }}" data-exam-subtype="{{ $exam->subtype }}" data-exam-skill="{{ $exam->skill }}">
                        @csrf

                        @include('instructor.exams.questions.partials.form-fields', [
                            'exam' => $exam,
                            'question' => null,
                            'questionTypes' => $questionTypes,
                            'skillLabels' => $skillLabels,
                            'showToolbar' => $showToolbar,
                        ])

                        <button type="submit" class="btn btn-primary w-100">Thêm câu hỏi</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div id="questionSelectionMessage" >
                    Hãy chọn skill và part.
                </div>
                @forelse($questions as $question)
                    <div class="question-panel mb-3" data-section-skill="{{ $question->section_skill }}" data-part-number="{{ $question->part_number }}">
                        <div class="d-flex justify-content-between gap-3 align-items-start mb-3">
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge text-bg-dark">#{{ $question->order }}</span>
                                @if($exam->type === 'exam')
                                    <span class="badge text-bg-primary">{{ $skillLabels[$question->section_skill] ?? 'Chưa chọn skill' }}</span>
                                    @if($question->part_number)
                                        <span class="badge text-bg-info">Part {{ $question->part_number }}</span>
                                    @endif
                                @endif
                                <span class="badge text-bg-secondary">{{ $questionTypes[$question->question_type] ?? $question->question_type }}</span>
                            </div>
                            <form method="POST" action="{{ route('instructor.exams.questions.destroy', [$exam, $question]) }}"
                                onsubmit="return confirm('Xóa câu hỏi này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>

                        <form method="POST" action="{{ route('instructor.exams.questions.update', [$exam, $question]) }}" class="question-form"
                            enctype="multipart/form-data"
                            data-exam-type="{{ $exam->type }}" data-exam-subtype="{{ $exam->subtype }}" data-exam-skill="{{ $exam->skill }}">
                            @csrf
                            @method('PUT')

                            @include('instructor.exams.questions.partials.form-fields', [
                                'exam' => $exam,
                                'question' => $question,
                                'questionTypes' => $questionTypes,
                                'skillLabels' => $skillLabels,
                                'showToolbar' => $showToolbar,
                            ])

                            <div class="text-end">
                                <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                @empty
                    {{-- <div class="alert alert-info">
                        Đề này chưa có câu hỏi. Thêm câu hỏi đầu tiên ở form bên trái.
                    </div> --}}
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .question-blueprint,
        .question-panel {
            background: #fff;
            border: 1px solid #e5e0d8;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .question-panel h2 {
            font-size: 22px;
            margin-bottom: 18px;
            color: #3e2723;
        }
    </style>
@endsection

@push('scripts')
    <script>
        function syncQuestionForm(form) {
            const examType = form.dataset.examType;
            const examSubtype = form.dataset.examSubtype;
            const examSkill = form.dataset.examSkill;
            const sectionSelect = form.querySelector('[name="section_skill"]');
            const partField = form.querySelector('.part-field');
            const partSelect = form.querySelector('[name="part_number"]');
            const typeSelect = form.querySelector('.question-type-select');
            const optionsField = form.querySelector('.options-field');
            const attachmentField = form.querySelector('.attachment-field');
            const attachmentInput = form.querySelector('[name="prompt_attachment"]');
            const attachmentHint = form.querySelector('.attachment-hint');
            const answerWrapper = form.querySelector('.correct-answer-wrapper');
            const answerLabel = form.querySelector('.correct-answer-label');
            const answerHint = form.querySelector('.answer-hint');

            const currentSkill = sectionSelect?.value || activeSkill || (examType === 'practice' ? examSkill : null);
            const needsPart = ['reading', 'listening'].includes(currentSkill);
            const currentType = typeSelect?.value;
            const isListeningType = currentType === 'text';
            const isSpeakingType = currentType === 'speaking';
            const isWritingType = currentType === 'writing';
            const showOptions = ['multiple_choice', 'checkbox', 'text'].includes(currentType);

            if (partField && partSelect) {
                const showPart = needsPart && !(examType === 'practice' && examSubtype === 'single');
                partField.style.display = showPart ? 'block' : 'none';
                partSelect.disabled = !showPart;

                if (!showPart) {
                    partSelect.value = examType === 'practice' && examSubtype === 'single' ? '1' : '';
                }
            }

            if (optionsField) {
                optionsField.style.display = showOptions ? 'block' : 'none';
            }

            if (attachmentField && attachmentInput) {
                attachmentField.style.display = (isListeningType || isSpeakingType) ? 'block' : 'none';
                if (isListeningType) {
                    attachmentInput.accept = '.mp3,.wav,.mp4';
                    // attachmentHint.textContent = 'Tải lên file ghi âm để làm đề bài Listening.';
                } else if (isSpeakingType) {
                    attachmentInput.accept = '.jpg,.jpeg,.png,.gif';
                    // attachmentHint.textContent = 'Tải lên ảnh để kèm đề bài Speaking.';
                } else {
                    attachmentInput.accept = '.jpg,.jpeg,.png,.gif,.mp3,.wav,.mp4,.pdf';
                    attachmentHint.textContent = '';
                }
            }

            if (answerWrapper) {
                answerWrapper.style.display = showOptions ? 'block' : 'none';
            }

            if (answerLabel) {
                answerLabel.textContent = isWritingType || isSpeakingType
                    ? 'Gợi ý chấm / tiêu chí chấm'
                    : 'Đáp án đúng / gợi ý đáp án';
            }

            // if (answerHint) {
            //     answerHint.textContent = isWritingType || isSpeakingType
            //         ? 'Writing/Speaking không có đáp án đúng cố định.'
            //         : 'Nhập đáp án đúng cho Reading/Listening.';
            // }
        }

        const questionTypeStorageKey = 'instructorExamLastQuestionType';
        let activeSkill = '{{ old('section_skill') }}' || null;
        let activePart = '{{ old('part_number') }}' || null;

        // Server-provided exam context
        const pageExamType = '{{ $exam->type }}';
        const pageExamSubtype = '{{ $exam->subtype }}';
        const pageExamSkill = '{{ $exam->skill }}';
        const showToolbar = pageExamType === 'exam' || (pageExamType === 'practice' && pageExamSubtype === 'full');

        function updateButtonState(buttons, activeValue, dataAttr) {
            buttons.forEach(btn => {
                const val = btn.dataset[dataAttr];
                if (String(val) === String(activeValue)) {
                    btn.classList.remove('btn-outline-primary', 'btn-outline-secondary');
                    btn.classList.add('btn-primary');
                } else {
                    btn.classList.remove('btn-primary');
                    if (btn.classList.contains('skill-btn')) btn.classList.add('btn-outline-primary');
                    else btn.classList.add('btn-outline-secondary');
                }
            });
        }

        function applyToolbarToForm(form) {
            const sectionSelect = form.querySelector('[name="section_skill"]');
            const partSelect = form.querySelector('[name="part_number"]');

            form.querySelectorAll('input.toolbar-section-skill, input.toolbar-part-number').forEach(el => el.remove());

            if (activeSkill) {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'section_skill';
                hidden.classList.add('toolbar-section-skill');
                hidden.value = activeSkill;
                form.appendChild(hidden);
            }

            if (activePart) {
                const hiddenPart = document.createElement('input');
                hiddenPart.type = 'hidden';
                hiddenPart.name = 'part_number';
                hiddenPart.classList.add('toolbar-part-number');
                hiddenPart.value = activePart;
                form.appendChild(hiddenPart);
            }

            syncQuestionForm(form);
        }

        function applyToolbarToAllForms() {
            document.querySelectorAll('.question-form').forEach(applyToolbarToForm);
        }

        function shouldShowQuestionList() {
            if (!showToolbar) {
                return true;
            }

            if (pageExamType === 'exam') {
                return !!(activeSkill && activePart);
            }

            if (pageExamType === 'practice' && pageExamSubtype === 'full') {
                return !!activePart;
            }

            return true;
        }

        function filterQuestionList() {
            const message = document.getElementById('questionSelectionMessage');
            const showList = shouldShowQuestionList();
            let visibleCount = 0;

            document.querySelectorAll('.question-panel.mb-3').forEach(panel => {
                const panelSkill = panel.dataset.sectionSkill || panel.getAttribute('data-section-skill');
                const panelPart = panel.dataset.partNumber || panel.getAttribute('data-part-number');

                let match = true;

                if (activeSkill) {
                    match = match && String(panelSkill) === String(activeSkill);
                }

                if (activePart) {
                    match = match && String(panelPart) === String(activePart);
                }

                const isVisible = showList && match;
                panel.style.display = isVisible ? '' : 'none';
                if (isVisible) {
                    visibleCount++;
                }
            });

            if (message) {
                if (!showList) {
                    message.textContent = pageExamType === 'exam'
                        ? 'Hãy chọn skill và part.'
                        : 'Hãy chọn part.';
                    message.classList.remove('d-none');
                } else if (visibleCount === 0) {
                    message.textContent = 'Chưa có câu hỏi phù hợp với lựa chọn này.';
                    message.classList.remove('d-none');
                } else {
                    message.classList.add('d-none');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const createForm = document.getElementById('createQuestionForm');
            const rememberedType = localStorage.getItem(questionTypeStorageKey);

            if (createForm && rememberedType) {
                const createTypeSelect = createForm.querySelector('.question-type-select');
                if (createTypeSelect) {
                    createTypeSelect.value = rememberedType;
                }
            }

            document.querySelectorAll('.question-form').forEach(form => {
                syncQuestionForm(form);

                form.querySelector('[name="section_skill"]')?.addEventListener('change', () => syncQuestionForm(form));
                form.querySelector('.question-type-select')?.addEventListener('change', function () {
                    syncQuestionForm(form);
                    if (form.id === 'createQuestionForm') {
                        localStorage.setItem(questionTypeStorageKey, this.value);
                    }
                });
            });

            const skillButtons = document.querySelectorAll('.skill-btn');
            const partButtons = document.querySelectorAll('.part-btn');
            const partClear = document.querySelector('.part-clear-btn');

            if (!activeSkill && pageExamType === 'practice' && pageExamSubtype === 'full') {
                activeSkill = pageExamSkill || null;
            }

            updateButtonState(skillButtons, activeSkill, 'skill');
            updateButtonState(partButtons, activePart, 'part');
            applyToolbarToAllForms();
            filterQuestionList();
            updateCreateFormVisibility();

            skillButtons.forEach(btn => btn.addEventListener('click', function (event) {
                event.preventDefault();
                activeSkill = this.dataset.skill;
                if (!['reading', 'listening'].includes(activeSkill)) {
                    activePart = null;
                    updateButtonState(partButtons, activePart, 'part');
                }
                updateButtonState(skillButtons, activeSkill, 'skill');
                applyToolbarToAllForms();
                filterQuestionList();
                updateCreateFormVisibility();
            }));

            partButtons.forEach(btn => btn.addEventListener('click', function (event) {
                event.preventDefault();
                activePart = this.dataset.part;
                updateButtonState(partButtons, activePart, 'part');
                applyToolbarToAllForms();
                filterQuestionList();
                updateCreateFormVisibility();
            }));

            partClear?.addEventListener('click', function (event) {
                event.preventDefault();
                activePart = null;
                updateButtonState(partButtons, activePart, 'part');
                applyToolbarToAllForms();
                filterQuestionList();
                updateCreateFormVisibility();
            });
        });

        function isSelectionValidForCreate() {
            // exam: need skill + part
            if (pageExamType === 'exam') {
                return !!(activeSkill && activePart);
            }

            // practice full: skill fixed, need part
            if (pageExamType === 'practice' && pageExamSubtype === 'full') {
                return !!activePart;
            }

            // practice single: always show (part 1 implied)
            if (pageExamType === 'practice' && pageExamSubtype === 'single') {
                return true;
            }

            return false;
        }

        function updateCreateFormVisibility() {
            const panel = document.getElementById('createQuestionPanel');
            const form = document.getElementById('createQuestionForm');
            if (!panel || !form) return;

            if (isSelectionValidForCreate()) {
                panel.style.display = '';
            } else {
                panel.style.display = 'none';
            }

            // ensure toolbar hidden inputs applied to the create form too
            applyToolbarToForm(form);
        }
    </script>
@endpush
