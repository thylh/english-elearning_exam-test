<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kết Quả Bài Làm</title>
    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .result-container {
            max-width: 860px;
            margin: 40px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .result-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .result-header h1 {
            font-size: 2.2rem;
            color: #1f2d6f;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .result-badge {
            display: inline-block;
            padding: 6px 16px;
            background: #eef2ff;
            color: #4f46e5;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .score-card {
            background: linear-gradient(135deg, #1f2d6f 0%, #1e3a8a 100%);
            color: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            margin-bottom: 35px;
            box-shadow: 0 10px 30px rgba(30, 58, 138, 0.15);
        }
        .score-val {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 10px;
        }
        .score-lbl {
            font-size: 1rem;
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .detail-section {
            margin-bottom: 35px;
        }
        .detail-title {
            font-size: 1.3rem;
            color: #1f2d6f;
            font-weight: 600;
            margin-bottom: 20px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 10px;
        }
        .question-list {
            display: grid;
            gap: 14px;
        }
        .question-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .question-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.02);
        }
        .question-card summary {
            list-style: none;
            cursor: pointer;
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }
        .question-card summary::-webkit-details-marker {
            display: none;
        }
        .question-summary-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .question-summary-title {
            font-weight: 700;
            color: #1f2d6f;
            font-size: 1rem;
        }
        .question-summary-subtitle {
            color: #64748b;
            font-size: 0.92rem;
        }
        .question-detail-body {
            padding: 0 20px 20px;
            border-top: 1px solid #e2e8f0;
        }
        .question-detail-grid {
            display: grid;
            gap: 12px;
            margin-top: 16px;
        }
        .question-detail-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .question-detail-value {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 15px;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #334155;
        }
        .q-status {
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
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
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            margin-top: 10px;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #334155;
        }
        .feedback-box {
            margin-top: 15px;
            padding: 15px;
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            border-radius: 0 12px 12px 0;
            font-size: 0.95rem;
        }
        .feedback-box.auto {
            background: #eff6ff;
            border-left-color: #3b82f6;
        }
        .audio-player-wrapper {
            margin-top: 10px;
        }
        .back-actions {
            display: flex;
            justify-content: center;
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
            background: #1f2d6f;
            color: white;
        }
        .btn-action-primary:hover {
            background: #162050;
            transform: translateY(-1px);
        }
        .empty-state {
            text-align: center;
            padding: 18px 0;
            color: #64748b;
        }
    </style>
</head>
<body style="background: #f1f5f9; font-family: system-ui, -apple-system, sans-serif;">
    @php
        $returnUrl = $returnUrl ?? url('/practice');
        $returnLabel = $returnLabel ?? 'Quay lại Practice';
    @endphp
    <div class="practice-layout">
        <main class="practice-content" style="padding: 20px;">
            <div class="result-container">
                <div class="result-header">
                    <span class="result-badge">{{ $exam->skill ?? 'General' }}</span>
                    <h1>Kết quả: {{ $exam->title }}</h1>
                </div>

                @php
                    $skill = $exam->skill ?? '';
                    $isWritingOrSpeaking = in_array($skill, ['writing', 'speaking']);
                @endphp

                <div class="score-card">
                    @if($isWritingOrSpeaking)
                        @if(isset($details[0]['status']) && $details[0]['status'] === 'graded')
                    <div class="score-val">{{ $score }}/100</div>
                            <div class="score-lbl">Điểm số trung bình /100</div>
                        @else
                            <div class="score-val" style="font-size: 2.2rem; padding: 10px 0;">Đang chờ chấm</div>
                            <div class="score-lbl">Bài làm của bạn đang chờ giáo viên chấm điểm</div>
                        @endif
                    @else
                        <div class="score-val">{{ $score }}/100</div>
                        <div class="score-lbl">Đúng {{ $correct ?? 0 }} / {{ $total }} câu</div>
                    @endif
                </div>

                <div class="detail-section">
                    <div class="detail-title">
                        <i class="fa-solid fa-list-check" style="margin-right: 8px;"></i> Chi tiết bài làm
                    </div>

                    @if(empty($details))
                        <div class="empty-state">Không có dữ liệu chi tiết.</div>
                    @else
                        <div class="question-list">
                            @foreach($details as $index => $d)
                                @php
                                    $isCorrect = array_key_exists('correct', $d) ? (bool) $d['correct'] : null;
                                    $statusLabel = ($d['status'] ?? null) === 'graded' ? 'Đã chấm' : 'Chờ chấm';
                                    $statusClass = array_key_exists('correct', $d)
                                        ? ($isCorrect ? 'correct' : 'wrong')
                                        : (($d['status'] ?? null) === 'graded' ? 'graded' : 'pending');

                                    $submittedValue = $d['submitted'] ?? null;
                                    if (is_array($submittedValue)) {
                                        $submittedValue = implode(', ', $submittedValue);
                                    }

                                    $expectedValue = $d['expected'] ?? null;
                                    if (is_array($expectedValue)) {
                                        $expectedValue = implode(', ', $expectedValue);
                                    }
                                @endphp
                                <details class="question-card">
                                    <summary>
                                        <div class="question-summary-main">
                                            <div class="question-summary-title">Câu {{ $index + 1 }}</div>
                                            <div class="question-summary-subtitle">
                                                {{ Str::limit($d['question_text'] ?? 'Không có nội dung câu hỏi.', 110) }}
                                            </div>
                                        </div>
                                        <span class="q-status {{ $statusClass }}">
                                            {{ $isCorrect === true ? 'Đúng' : ($isCorrect === false ? 'Sai' : $statusLabel) }}
                                        </span>
                                    </summary>

                                    <div class="question-detail-body">
                                        <div class="question-detail-grid">
                                            <div>
                                                <span class="question-detail-label">Nội dung câu hỏi</span>
                                                <div class="question-detail-value" style="white-space: pre-wrap;">
                                                    {{ $d['question_text'] ?? 'Không có nội dung câu hỏi.' }}
                                                </div>
                                            </div>

                                            @if(array_key_exists('correct', $d))
                                                <div>
                                                    <span class="question-detail-label">Bạn đã chọn</span>
                                                    <div class="question-detail-value">
                                                        {{ $submittedValue !== null && $submittedValue !== '' ? $submittedValue : 'Không có câu trả lời.' }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <span class="question-detail-label">Đáp án đúng</span>
                                                    <div class="question-detail-value">
                                                        {{ $expectedValue !== null && $expectedValue !== '' ? $expectedValue : 'Không có đáp án chuẩn.' }}
                                                    </div>
                                                </div>
                                            @else
                                                <div>
                                                    <span class="question-detail-label">
                                                        {{ in_array($skill, ['writing', 'speaking']) ? 'Bài làm của bạn' : 'Câu trả lời' }}
                                                    </span>
                                                    <div class="question-detail-value" style="white-space: pre-wrap;">
                                                        {{ $submittedValue !== null && $submittedValue !== '' ? $submittedValue : 'Không có câu trả lời.' }}
                                                    </div>
                                                </div>
                                            @endif

                                            @if($skill === 'speaking' && isset($d['audio_path']) && $d['audio_path'])
                                                @php
                                                    $subId = \App\Models\Submission::where('exam_id', $exam->id)
                                                        ->where('question_id', $d['question_id'])
                                                        ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                                                        ->where('type', 'speaking')
                                                        ->first()?->id;
                                                @endphp
                                                <div>
                                                    <span class="question-detail-label">File ghi âm</span>
                                                    <div class="question-detail-value">
                                                        @if($subId)
                                                            <audio controls src="{{ route('media.speaking_submission', $subId) }}" style="width: 100%;"></audio>
                                                        @else
                                                            Không tải được file ghi âm.
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if(isset($d['auto_score']))
                                                <div>
                                                    <span class="question-detail-label">Điểm tự động</span>
                                                    <div class="feedback-box auto" style="margin-top: 0;">
                                                        <strong><i class="fa-solid fa-robot"></i> Điểm tự động:</strong> {{ $d['auto_score'] }}/100<br>
                                                        <strong>Nhận xét:</strong> {{ $d['auto_feedback'] ?? 'Không có.' }}
                                                    </div>
                                                </div>
                                            @endif

                                            @if(isset($d['transcript']) && $d['transcript'])
                                                <div>
                                                    <span class="question-detail-label">Transcript</span>
                                                    <div class="question-detail-value" style="white-space: pre-wrap;">{{ $d['transcript'] }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="back-actions">
                    <a href="{{ $returnUrl }}" class="btn-action btn-action-primary">
                        <i class="fa-solid fa-arrow-left"></i> {{ $returnLabel }}
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
