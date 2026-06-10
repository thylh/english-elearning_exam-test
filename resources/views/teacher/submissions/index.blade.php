<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chấm thi - English For You</title>

    {{-- Google Font (giống layouts/app.blade.php) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Vite: animation + english-for-you --}}
    @vite([
        'resources/css/app.css',
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])
</head>

<body class="page-transition">
    @include('partials.dashboard-header')

    <div class="submissions-page-wrap page-transition">

        <div class="submissions-wrapper">

            {{-- PAGE HEADER --}}
            <div class="submissions-page-header">
                <div class="submissions-page-title">
                    <i class="fa-solid fa-clipboard-check"></i>
                    Chấm thi
                </div>
            </div>

            {{-- ALERT --}}
            @if(session('success'))
                <div class="submissions-alert success auto-hide-alert" id="submissions-alert">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- FILTER BAR --}}
            <div class="submissions-filter-bar">
                <span class="submissions-filter-label">
                    <i class="fa-solid fa-filter"></i> Trạng thái:
                </span>
                <a href="{{ route('teacher.submissions.index', ['status' => 'pending', 'type' => $type]) }}"
                   class="submissions-filter-btn {{ $status === 'pending' ? 'active' : '' }}">
                    <i class="fa-solid fa-clock"></i> Chờ chấm
                </a>
                <a href="{{ route('teacher.submissions.index', ['status' => 'graded', 'type' => $type]) }}"
                   class="submissions-filter-btn {{ $status === 'graded' ? 'active' : '' }}">
                    <i class="fa-solid fa-check-circle"></i> Đã chấm
                </a>

                <div class="submissions-filter-divider"></div>

                <span class="submissions-filter-label">Loại:</span>
                <a href="{{ route('teacher.submissions.index', ['status' => $status, 'type' => 'all']) }}"
                   class="submissions-filter-btn {{ $type === 'all' ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group"></i> Tất cả
                </a>
                <a href="{{ route('teacher.submissions.index', ['status' => $status, 'type' => 'writing']) }}"
                   class="submissions-filter-btn {{ $type === 'writing' ? 'active-writing' : '' }}">
                    <i class="fa-solid fa-pen-nib"></i> Writing
                </a>
                <a href="{{ route('teacher.submissions.index', ['status' => $status, 'type' => 'speaking']) }}"
                   class="submissions-filter-btn {{ $type === 'speaking' ? 'active-speaking' : '' }}">
                    <i class="fa-solid fa-microphone-lines"></i> Speaking
                </a>
            </div>

            {{-- SUBMISSION LIST --}}
            @forelse($submissions as $sub)
                <div class="submission-card">

                    {{-- CARD HEADER --}}
                    <div class="submission-card-header">
                        <div>
                            <div class="submission-card-meta">
                                @if($sub->type === 'writing')
                                    <span class="badge-type badge-writing">
                                        <i class="fa-solid fa-pen-nib"></i> Writing
                                    </span>
                                @else
                                    <span class="badge-type badge-speaking">
                                        <i class="fa-solid fa-microphone-lines"></i> Speaking
                                    </span>
                                @endif
                                <span class="badge-status {{ $sub->status === 'graded' ? 'badge-graded' : 'badge-pending' }}">
                                    {{ $sub->status === 'graded' ? 'Đã chấm' : 'Chờ chấm' }}
                                </span>
                            </div>
                            <div class="submission-card-title">
                                {{ $sub->exam->title ?? 'Bài thi' }}
                            </div>
                            <div class="submission-card-subtitle">
                                {{ Str::limit($sub->examQuestion->question_text ?? '—', 90) }}
                            </div>
                            @if($sub->user)
                                <div class="submission-card-student">
                                    <i class="fa-solid fa-user-graduate"></i>
                                    {{ $sub->user->name }} ({{ $sub->user->email }})
                                </div>
                            @else
                                <div class="submission-card-student">
                                    <i class="fa-solid fa-user-slash"></i>
                                    Ẩn danh
                                </div>
                            @endif
                        </div>
                        <div class="submission-card-time">
                            <i class="fa-regular fa-clock"></i>
                            {{ $sub->created_at->diffForHumans() }}
                        </div>
                    </div>

                    {{-- ANSWER CONTENT --}}
                    <div class="submission-answer-section">
                        @if($sub->type === 'writing')
                            <div class="submission-answer-label">
                                <i class="fa-solid fa-file-lines"></i>
                                Bài viết của học viên:
                            </div>
                            <div class="submission-answer-text">{{ $sub->answer_text ?: '(Không có nội dung)' }}</div>
                        @else
                            <div class="submission-answer-label">
                                <i class="fa-solid fa-headphones"></i>
                                File ghi âm:
                            </div>
                            @if($sub->audio_path)
                                <div class="submission-audio-wrap">
                                    <audio controls src="{{ route('media.speaking_submission', $sub->id) }}"></audio>
                                </div>
                                @if($sub->answer_text)
                                    <div class="submission-answer-label submission-answer-label--mt">
                                        <i class="fa-solid fa-closed-captioning"></i>
                                        Transcript (nhận dạng tự động):
                                    </div>
                                    <div class="submission-transcript">{{ $sub->answer_text }}</div>
                                @endif
                            @else
                                <div class="submission-no-audio">
                                    <i class="fa-solid fa-ban"></i> Không có file ghi âm.
                                </div>
                            @endif
                        @endif
                    </div>

                    {{-- AUTO SCORE --}}
                    @if(!is_null($sub->auto_score))
                        <div class="submission-auto-score">
                            <i class="fa-solid fa-robot"></i>
                            <div>
                                Điểm tự động:
                                <span class="submission-auto-score-val">{{ $sub->auto_score }}/100</span>
                                @if($sub->auto_feedback)
                                    <div class="submission-auto-score-feedback">{{ $sub->auto_feedback }}</div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- GRADED RESULT --}}
                    @if($sub->status === 'graded' && !is_null($sub->manual_score))
                        <div class="submission-graded-result">
                            <div class="submission-graded-score">
                                {{ $sub->manual_score }}<span>/100</span>
                            </div>
                            <div>
                                @if($sub->manual_feedback)
                                    <div class="submission-graded-feedback">{{ $sub->manual_feedback }}</div>
                                @endif
                                <div class="submission-graded-by">
                                    <i class="fa-solid fa-user-check"></i>
                                    Chấm bởi: {{ $sub->gradedBy->name ?? 'N/A' }}
                                    &nbsp;·&nbsp;
                                    {{ $sub->graded_at?->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- GRADING FORM --}}
                    <div class="submission-grading-form">
                        <div class="submission-grading-title">
                            <i class="fa-solid fa-pen-to-square"></i>
                            {{ $sub->status === 'graded' ? 'Cập nhật điểm' : 'Chấm điểm thủ công' }}
                        </div>
                        <form action="{{ route('teacher.submissions.update', $sub->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $status }}">
                            <input type="hidden" name="type" value="{{ $type }}">
                            <div class="submission-form-row">
                                <div class="submission-form-group">
                                    <label for="score_{{ $sub->id }}">Điểm (0 – 100)</label>
                                    <input type="number"
                                           id="score_{{ $sub->id }}"
                                           name="manual_score"
                                           min="0" max="100" step="0.1"
                                           value="{{ $sub->manual_score ?? '' }}"
                                           placeholder="VD: 78.5"
                                           required>
                                </div>
                                <div class="submission-form-group">
                                    <label for="fb_{{ $sub->id }}">Nhận xét (tuỳ chọn)</label>
                                    <textarea id="fb_{{ $sub->id }}"
                                              name="manual_feedback"
                                              rows="2"
                                              placeholder="Nhận xét cho học viên...">{{ $sub->manual_feedback ?? '' }}</textarea>
                                </div>
                                <div class="submission-form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="submission-btn-grade">
                                        <i class="fa-solid fa-paper-plane"></i>
                                        Lưu điểm
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            @empty
                <div class="submissions-empty">
                    <div class="submissions-empty-icon">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                    <h3>Không có bài nộp nào</h3>
                    <p>
                        @if($status === 'pending')
                            Chưa có bài nào chờ chấm{{ $type !== 'all' ? " ($type)" : '' }}.
                        @else
                            Chưa có bài nào đã chấm{{ $type !== 'all' ? " ($type)" : '' }}.
                        @endif
                    </p>
                </div>
            @endforelse

            {{-- PAGINATION --}}
            @if($submissions->hasPages())
                <div class="submissions-pagination">
                    {{ $submissions->links() }}
                </div>
            @endif

        </div>
    </div>
</body>
</html>
