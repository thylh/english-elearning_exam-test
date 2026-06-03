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
        'text' => 'Điền câu trả lời ngắn',
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
            <h1>Nội dung đề thi</h1>
            <p>{{ $exam->title }}</p>
        </div>
    </section>

    <div class="container my-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Không thể lưu câu hỏi.</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center gap-3 mb-3 flex-wrap">
            <a href="{{ route('instructor.exams.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Quay lại danh sách đề
            </a>
            <div class="text-muted">{{ $questions->count() }} câu hỏi</div>
        </div>

        <div class="question-blueprint mb-4">
            <strong>Cấu trúc nội dung:</strong>
            @if($exam->type === 'exam')
                tạo đủ 4 phần Reading, Listening, Writing, Speaking.
            @elseif($exam->subtype === 'full')
                tạo full bài {{ $skillLabels[$exam->skill] ?? $exam->skill }}{{ $needsPart($exam->skill) ? ' gồm Part 1-4.' : '.' }}
            @else
                tạo đề lẻ {{ $skillLabels[$exam->skill] ?? $exam->skill }} - Part 1.
            @endif
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="question-panel">
                    <h2>Thêm câu hỏi</h2>

                    <form method="POST" action="{{ route('instructor.exams.questions.store', $exam) }}" class="question-form"
                        data-exam-type="{{ $exam->type }}" data-exam-subtype="{{ $exam->subtype }}" data-exam-skill="{{ $exam->skill }}">
                        @csrf

                        @include('instructor.exams.questions.partials.form-fields', [
                            'exam' => $exam,
                            'question' => null,
                            'questionTypes' => $questionTypes,
                            'skillLabels' => $skillLabels,
                        ])

                        <button type="submit" class="btn btn-primary w-100">Thêm câu hỏi</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                @forelse($questions as $question)
                    <div class="question-panel mb-3">
                        <div class="d-flex justify-content-between gap-3 align-items-start mb-3">
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge text-bg-dark">#{{ $question->order }}</span>
                                <span class="badge text-bg-primary">{{ $skillLabels[$question->section_skill] ?? 'Chưa chọn skill' }}</span>
                                @if($question->part_number)
                                    <span class="badge text-bg-info">Part {{ $question->part_number }}</span>
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
                            data-exam-type="{{ $exam->type }}" data-exam-subtype="{{ $exam->subtype }}" data-exam-skill="{{ $exam->skill }}">
                            @csrf
                            @method('PUT')

                            @include('instructor.exams.questions.partials.form-fields', [
                                'exam' => $exam,
                                'question' => $question,
                                'questionTypes' => $questionTypes,
                                'skillLabels' => $skillLabels,
                            ])

                            <div class="text-end">
                                <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="alert alert-info">
                        Đề này chưa có câu hỏi. Thêm câu hỏi đầu tiên ở form bên trái.
                    </div>
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

            const currentSkill = examType === 'practice' ? examSkill : sectionSelect?.value;
            const needsPart = ['reading', 'listening'].includes(currentSkill);

            if (partField && partSelect) {
                const showPart = needsPart && !(examType === 'practice' && examSubtype === 'single');
                partField.style.display = showPart ? 'block' : 'none';
                partSelect.disabled = !showPart;

                if (!showPart) {
                    partSelect.value = examType === 'practice' && examSubtype === 'single' ? '1' : '';
                }
            }

            if (optionsField && typeSelect) {
                optionsField.style.display = ['multiple_choice', 'checkbox'].includes(typeSelect.value) ? 'block' : 'none';
            }
        }

        document.querySelectorAll('.question-form').forEach(form => {
            syncQuestionForm(form);

            form.querySelector('[name="section_skill"]')?.addEventListener('change', () => syncQuestionForm(form));
            form.querySelector('.question-type-select')?.addEventListener('change', () => syncQuestionForm(form));
        });
    </script>
@endpush
