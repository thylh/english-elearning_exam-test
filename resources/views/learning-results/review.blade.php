@extends('layouts.app')

@push('head')
    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .result-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 35px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(141, 110, 99, 0.08);
            border: 1px solid rgba(232, 215, 195, 0.4);
            font-family: 'Be Vietnam Pro', system-ui, -apple-system, sans-serif;
        }
        .result-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .result-header h1 {
            font-size: 2.2rem;
            color: #3e2723;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .result-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .result-badge.reading { background: #eff6ff; color: #1e40af; }
        .result-badge.listening { background: #ecfdf5; color: #065f46; }
        .result-badge.writing { background: #fffbeb; color: #92400e; }
        .result-badge.speaking { background: #fdf2f8; color: #9d174d; }

        .score-card {
            background: linear-gradient(135deg, #8d6e63 0%, #5d4037 100%);
            color: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 35px;
            box-shadow: 0 10px 30px rgba(93, 64, 55, 0.2);
        }
        .score-val {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 10px;
        }
        .score-lbl {
            font-size: 1rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .detail-section {
            margin-bottom: 35px;
        }
        .detail-title {
            font-size: 1.3rem;
            color: #4e342e;
            font-weight: 600;
            margin-bottom: 25px;
            border-bottom: 2px solid #f5ebe1;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .question-card {
            background: #fffcf9;
            border: 1px solid #e8d7c3;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }
        .question-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(141, 110, 99, 0.05);
        }
        .q-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            font-weight: 600;
            color: #4e342e;
        }
        .q-status {
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .q-status.correct {
            background: #d1fae5;
            color: #065f46;
        }
        .q-status.wrong {
            background: #fee2e2;
            color: #991b1b;
        }
        .q-status.pending {
            background: #fef3c7;
            color: #92400e;
        }
        .q-status.graded {
            background: #dbeafe;
            color: #1e40af;
        }
        .answer-box {
            background: white;
            border: 1px solid #e8d7c3;
            border-radius: 12px;
            padding: 15px;
            margin-top: 10px;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #4e342e;
        }
        .audio-player-wrapper {
            margin-top: 10px;
        }
        .feedback-container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .feedback-box {
            padding: 15px;
            border-radius: 12px;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .feedback-box.auto {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            color: #1e3a8a;
        }
        .feedback-box.manual {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            color: #065f46;
        }
        .back-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 40px;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-action-primary {
            background: #8d6e63;
            color: white !important;
        }
        .btn-action-primary:hover {
            background: #795548;
            transform: translateY(-1px);
        }
        .btn-action-secondary {
            background: #f5ebe1;
            color: #5d4037 !important;
            border: 1px solid #e8d7c3;
        }
        .btn-action-secondary:hover {
            background: #e8d7c3;
            transform: translateY(-1px);
        }
    </style>
@endpush

@section('content')
    @include('partials.dashboard-header')

    @php
        $skill = strtolower($result->exam->skill ?? 'general');
        $isWritingOrSpeaking = in_array($skill, ['writing', 'speaking']);
        // Use submissions relation (relational DB) – $result->details column has been removed
        $submissions = $result->submissions->sortBy(fn($s) => $s->examQuestion?->order ?? $s->id);
    @endphp

    <div class="result-container">
        <div class="result-header">
            <span class="result-badge {{ $skill }}">
                @if($skill === 'reading')
                    <i class="fa-solid fa-book-open"></i> Đọc (Reading)
                @elseif($skill === 'listening')
                    <i class="fa-solid fa-headphones"></i> Nghe (Listening)
                @elseif($skill === 'writing')
                    <i class="fa-solid fa-pen-nib"></i> Viết (Writing)
                @elseif($skill === 'speaking')
                    <i class="fa-solid fa-microphone"></i> Nói (Speaking)
                @else
                    Tổng hợp
                @endif
            </span>
            <h1 style="margin-top: 15px;">Kết quả: {{ $result->exam->title }}</h1>
            <p style="color: #795548; font-size: 0.95rem;">Làm bài lúc: {{ $result->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="score-card">
            @if($isWritingOrSpeaking)
                @if($result->status === 'graded')
                    <div class="score-val">{{ $result->score }}/100</div>
                    <div class="score-lbl">Điểm số trung bình /100</div>
                @else
                    <div class="score-val" style="font-size: 2.2rem; padding: 10px 0;">Chờ chấm điểm</div>
                    <div class="score-lbl">Bài làm đang chờ giáo viên chấm và nhận xét</div>
                @endif
            @else
                <div class="score-val">{{ $result->score }}/100</div>
                <div class="score-lbl">Đúng {{ $result->correct_answers }} / {{ $result->total_questions }} câu</div>
            @endif
        </div>

        <div class="detail-section">
            <div class="detail-title">
                <i class="fa-solid fa-list-check"></i> Chi tiết bài làm
            </div>

            @if($skill === 'writing')
                @forelse($submissions as $index => $sub)
                    @php
                        $qText = $sub->examQuestion?->question_text ?? 'Câu hỏi';
                    @endphp
                    <div class="question-card">
                        <div class="q-header">
                            <span>Câu hỏi {{ $loop->iteration }}</span>
                            <span class="q-status {{ $sub->status === 'graded' ? 'graded' : 'pending' }}">
                                {{ $sub->status === 'graded' ? 'Đã chấm' : 'Chờ chấm' }}
                            </span>
                        </div>
                        <div style="margin-bottom: 15px; color: #795548; font-weight: 500;">
                            <strong>Đề bài:</strong> {!! nl2br(e($qText)) !!}
                        </div>
                        <div>
                            <strong>Bài viết của bạn:</strong>
                            <div class="answer-box" style="white-space: pre-wrap;">{{ $sub->answer_text ?? 'Không có câu trả lời.' }}</div>
                        </div>

                        <div class="feedback-container">
                            {{-- AI Feedback --}}
                            @if($sub->auto_score !== null)
                                <div class="feedback-box auto">
                                    <strong><i class="fa-solid fa-robot"></i> Điểm chấm tự động bằng AI:</strong> {{ $sub->auto_score }}/100<br>
                                    <strong>Nhận xét từ AI:</strong><br>
                                    <div style="margin-top: 5px; white-space: pre-wrap;">{{ $sub->auto_feedback ?? 'Không có.' }}</div>
                                </div>
                            @endif

                            {{-- Manual Feedback --}}
                            @if($sub->manual_score !== null)
                                <div class="feedback-box manual">
                                    <strong><i class="fa-solid fa-user-tie"></i> Điểm giáo viên chấm:</strong> {{ $sub->manual_score }}/100<br>
                                    <strong>Nhận xét từ giáo viên:</strong><br>
                                    <div style="margin-top: 5px; white-space: pre-wrap;">{{ $sub->manual_feedback ?? 'Không có.' }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p style="color: #795548; text-align: center;">Không có dữ liệu chi tiết bài làm.</p>
                @endforelse

            @elseif($skill === 'speaking')
                @forelse($submissions as $index => $sub)
                    @php
                        $qText = $sub->examQuestion?->question_text ?? 'Câu hỏi';
                    @endphp
                    <div class="question-card">
                        <div class="q-header">
                            <span>Câu hỏi {{ $loop->iteration }}</span>
                            <span class="q-status {{ $sub->status === 'graded' ? 'graded' : 'pending' }}">
                                {{ $sub->status === 'graded' ? 'Đã chấm' : 'Chờ chấm' }}
                            </span>
                        </div>
                        <div style="margin-bottom: 15px; color: #795548; font-weight: 500;">
                            <strong>Câu hỏi:</strong> {!! nl2br(e($qText)) !!}
                        </div>

                        <div style="margin-top: 15px;">
                            <strong>File ghi âm câu trả lời:</strong>
                            <div class="audio-player-wrapper">
                                @if($sub->audio_path)
                                    <audio controls src="{{ route('media.speaking_submission', $sub->id) }}" style="width: 100%; margin-top: 8px;"></audio>
                                @else
                                    <span class="text-danger">Không có file ghi âm</span>
                                @endif
                            </div>
                        </div>

                        @if($sub->answer_text)
                            <div style="margin-top: 15px;">
                                <strong>Bản dịch tự động từ giọng nói:</strong>
                                <div class="answer-box">{{ $sub->answer_text }}</div>
                            </div>
                        @endif

                        <div class="feedback-container">
                            {{-- AI Feedback --}}
                            @if($sub->auto_score !== null)
                                <div class="feedback-box auto">
                                    <strong><i class="fa-solid fa-robot"></i> Điểm chấm tự động bằng AI:</strong> {{ $sub->auto_score }}/100<br>
                                    <strong>Nhận xét từ AI:</strong><br>
                                    <div style="margin-top: 5px; white-space: pre-wrap;">{{ $sub->auto_feedback ?? 'Không có.' }}</div>
                                </div>
                            @endif

                            {{-- Manual Feedback --}}
                            @if($sub->manual_score !== null)
                                <div class="feedback-box manual">
                                    <strong><i class="fa-solid fa-user-tie"></i> Điểm giáo viên chấm:</strong> {{ $sub->manual_score }}/100<br>
                                    <strong>Nhận xét từ giáo viên:</strong><br>
                                    <div style="margin-top: 5px; white-space: pre-wrap;">{{ $sub->manual_feedback ?? 'Không có.' }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p style="color: #795548; text-align: center;">Không có dữ liệu chi tiết bài làm.</p>
                @endforelse

            @else
                {{-- Reading / Listening: derive correct/wrong from auto_score in submissions --}}
                @php
                    $questionsMap = $result->exam->questions->keyBy('id');
                @endphp

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px; margin-bottom: 35px;">
                    @foreach($submissions as $sub)
                        @php $isCorrect = ($sub->auto_score ?? 0) >= 1.0; @endphp
                        <div class="question-card" style="margin-bottom: 0; text-align: center; padding: 15px; border-radius: 12px;">
                            <div style="font-weight: 600; margin-bottom: 8px;">Câu {{ $loop->iteration }}</div>
                            <span class="q-status {{ $isCorrect ? 'correct' : 'wrong' }}">
                                {{ $isCorrect ? 'Đúng' : 'Sai' }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="detail-title" style="margin-top: 40px;">
                    <i class="fa-solid fa-circle-info"></i> Xem đáp án chi tiết
                </div>

                @foreach($submissions as $sub)
                    @php
                        $question = $questionsMap->get($sub->question_id);
                        $qText = $question?->question_text ?? 'Câu hỏi';
                        $isCorrect = ($sub->auto_score ?? 0) >= 1.0;
                        $correctAnswer = $question?->correct_answer;
                    @endphp
                    <div class="question-card" style="border-left: 5px solid {{ $isCorrect ? '#10b981' : '#ef4444' }};">
                        <div style="font-weight: 600; color: #3e2723; margin-bottom: 10px;">
                            Câu {{ $loop->iteration }}
                        </div>
                        <div style="color: #4e342e; margin-bottom: 15px;">
                            {!! $qText !!}
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.95rem;">
                            <div>
                                <strong>Đáp án của bạn:</strong>
                                <span style="color: {{ $isCorrect ? '#065f46' : '#991b1b' }}; font-weight: 600;">
                                    {{ $sub->answer_text ?? '(Bỏ trống)' }}
                                </span>
                            </div>
                            @if(!$isCorrect)
                                <div>
                                    <strong>Đáp án đúng:</strong>
                                    <span style="color: #065f46; font-weight: 600;">
                                        {{ is_array($correctAnswer) ? implode(', ', $correctAnswer) : ($correctAnswer ?? '') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="back-actions">
            <a href="{{ route('learning-results.index') }}" class="btn-action btn-action-primary">
                <i class="fa-solid fa-arrow-left"></i> Quay lại Kết quả học tập
            </a>
            <a href="/practice" class="btn-action btn-action-secondary">
                <i class="fa-solid fa-book"></i> Luyện tập thêm
            </a>
        </div>
    </div>
@endsection
