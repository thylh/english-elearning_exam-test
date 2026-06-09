@extends('layouts.app')

@push('head')
    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .results-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            font-family: 'Be Vietnam Pro', system-ui, -apple-system, sans-serif;
        }

        .results-header {
            margin-bottom: 35px;
            text-align: center;
        }

        .results-header h1 {
            font-size: 2.5rem;
            color: #3e2723;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .results-header p {
            font-size: 1.1rem;
            color: #795548;
        }

        /* GRID FOR CARDS */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .skill-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(141, 110, 99, 0.08);
            border: 1px solid rgba(232, 215, 195, 0.5);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .skill-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(141, 110, 99, 0.15);
        }

        .skill-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--skill-color, #8d6e63);
        }

        .skill-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .skill-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: var(--skill-light-bg, #f5ebe1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--skill-color, #8d6e63);
            font-size: 22px;
        }

        .skill-count {
            font-size: 0.85rem;
            color: #8d6e63;
            background: #f5ebe1;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 500;
        }

        .skill-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #4e342e;
            margin-bottom: 5px;
        }

        .skill-score {
            font-size: 2.2rem;
            font-weight: 800;
            color: #3e2723;
            margin-bottom: 15px;
            display: flex;
            align-items: baseline;
            gap: 5px;
        }

        .skill-score span {
            font-size: 1rem;
            font-weight: 500;
            color: #8d6e63;
        }

        .skill-progress-container {
            width: 100%;
            height: 8px;
            background: #f5ebe1;
            border-radius: 999px;
            overflow: hidden;
        }

        .skill-progress-bar {
            height: 100%;
            background: var(--skill-color, #8d6e63);
            border-radius: 999px;
            width: 0%;
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ANALYSIS CONTAINER */
        .analysis-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(141, 110, 99, 0.08);
            border: 1px solid rgba(232, 215, 195, 0.5);
            margin-bottom: 40px;
            display: grid;
            grid-template-columns: 2fr 3fr;
            gap: 40px;
        }

        @media (max-width: 768px) {
            .analysis-section {
                grid-template-columns: 1fr;
                gap: 25px;
            }
        }

        .analysis-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #3e2723;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .analysis-title i {
            color: #8d6e63;
        }

        .insight-box {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .insight-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
            border-radius: 12px;
            background: #fcf8f5;
            border: 1px dashed #e8d7c3;
        }

        .insight-item.strength {
            background: #effaf3;
            border-color: #d1fae5;
        }

        .insight-item.weakness {
            background: #fffbeb;
            border-color: #fef3c7;
        }

        .insight-item i {
            font-size: 20px;
            margin-top: 2px;
        }

        .insight-item.strength i {
            color: #10b981;
        }

        .insight-item.weakness i {
            color: #f59e0b;
        }

        .insight-content h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #3e2723;
            margin-bottom: 4px;
        }

        .insight-content p {
            font-size: 0.9rem;
            color: #795548;
        }

        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 5px;
        }

        .tag-badge {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .tag-badge.strength {
            background: #d1fae5;
            color: #065f46;
        }

        .tag-badge.weakness {
            background: #fee2e2;
            color: #991b1b;
        }

        /* HISTORY TABLE */
        .history-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(141, 110, 99, 0.08);
            border: 1px solid rgba(232, 215, 195, 0.5);
        }

        .history-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #3e2723;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .history-title i {
            color: #8d6e63;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .history-table th {
            padding: 16px 20px;
            font-weight: 600;
            color: #795548;
            border-bottom: 2px solid #f5ebe1;
            font-size: 0.95rem;
        }

        .history-table td {
            padding: 16px 20px;
            color: #4e342e;
            border-bottom: 1px solid #f5ebe1;
            font-size: 0.95rem;
            vertical-align: middle;
        }

        .history-table tr:last-child td {
            border-bottom: none;
        }

        .history-table tr:hover td {
            background: #fffcf9;
        }

        .skill-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .skill-badge.reading { background: #eff6ff; color: #1e40af; }
        .skill-badge.listening { background: #ecfdf5; color: #065f46; }
        .skill-badge.writing { background: #fffbeb; color: #92400e; }
        .skill-badge.speaking { background: #fdf2f8; color: #9d174d; }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-badge.graded {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-review {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #8d6e63;
            color: white !important;
            padding: 8px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-review:hover {
            background: #795548;
            transform: translateY(-1px);
        }

        .pagination-wrapper {
            margin-top: 25px;
            display: flex;
            justify-content: center;
        }

        .empty-history {
            text-align: center;
            padding: 50px 20px;
            color: #8d6e63;
        }

        .empty-history i {
            font-size: 50px;
            margin-bottom: 15px;
            opacity: 0.7;
        }
    </style>
@endpush

@section('content')
    @include('partials.dashboard-header')

    <div class="results-container">
        <div class="results-header">
            <h1>Kết Quả Học Tập</h1>
            <p>Xin chào, {{ Auth::user()->name }}. Dưới đây là bảng thống kê năng lực 4 kỹ năng của bạn.</p>
        </div>

        <!-- SKILLS DASHBOARD -->
        <div class="skills-grid">
            @foreach($skillsInfo as $key => $info)
                @php
                    $scoreValue = $info['avg'];
                    $hasScore = $scoreValue !== null;
                    $progressWidth = $hasScore ? min(100, max(0, (float) $scoreValue)) : 0;
                @endphp
                <div class="skill-card" style="--skill-color: {{ $info['color'] }}; --skill-light-bg: {{ $info['color'] }}15;">
                    <div class="skill-card-header">
                        <div class="skill-icon-wrapper">
                            <i class="fa-solid {{ $info['icon'] }}"></i>
                        </div>
                        <span class="skill-count">{{ $info['count'] }} bài đã làm</span>
                    </div>
                    <div class="skill-title">{{ $info['label'] }}</div>
                    <div class="skill-score">
                        @if($hasScore)
                            {{ $scoreValue }}<span>/100</span>
                        @else
                            --<span>Chưa làm</span>
                        @endif
                    </div>
                    <div class="skill-progress-container">
                        <div class="skill-progress-bar" style="width: {{ $progressWidth }}%;"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- DYNAMIC ANALYSIS -->
        <div class="analysis-section">
            <div>
                <div class="analysis-title">
                    <i class="fa-solid fa-brain"></i>
                    Đánh Giá Năng Lực
                </div>
                <div class="insight-box">
                    <div class="insight-item strength">
                        <i class="fa-solid fa-circle-check"></i>
                        <div class="insight-content">
                            <h4>Thế mạnh nổi trội</h4>
                            <div class="tag-list">
                                @forelse($strengths as $s)
                                    <span class="tag-badge strength">{{ $s }}</span>
                                @empty
                                    <span class="text-muted" style="font-size: 0.9rem; color: #795548;">Chưa xác định</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="insight-item weakness">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div class="insight-content">
                            <h4>Điểm cần cải thiện</h4>
                            <div class="tag-list">
                                @forelse($weaknesses as $w)
                                    <span class="tag-badge weakness">{{ $w }}</span>
                                @empty
                                    <span class="text-muted" style="font-size: 0.9rem; color: #795548;">Chưa xác định</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="analysis-title">
                    <i class="fa-solid fa-lightbulb"></i>
                    Đề Xuất Học Tập
                </div>
                <div style="background: #fffcf9; border: 1px solid #f5ebe1; padding: 25px; border-radius: 16px; height: calc(100% - 45px); box-sizing: border-box; line-height: 1.7; color: #5d4037;">
                    @if($totalTests === 0)
                        <p>Bạn chưa hoàn thành bài làm thử nào trên hệ thống. Hãy bắt đầu bằng cách truy cập trang <strong>Practice</strong> hoặc <strong>Exam test</strong> ở thanh menu để làm bài đầu tiên, hệ thống sẽ dựa vào đó để đề xuất lộ trình ôn tập cụ thể cho bạn.</p>
                    @else
                        @php
                            $hasReadingOrListeningWeak = in_array('Reading', $weaknesses) || in_array('Listening', $weaknesses);
                            $hasWritingOrSpeakingWeak = in_array('Writing', $weaknesses) || in_array('Speaking', $weaknesses);
                        @endphp
                        
                        @if(count($strengths) === 4)
                            <p><i class="fa-solid fa-trophy" style="color: #f59e0b; margin-right: 6px;"></i> <strong>Tuyệt vời!</strong> Bạn đang duy trì phong độ cực kỳ ấn tượng ở cả 4 kỹ năng. Để tối ưu hóa điểm số và sẵn sàng cho kỳ thi thực tế, hãy làm thêm nhiều đề thi trọn bộ (Full Test) tại mục <strong>Exam test</strong> để rèn luyện khả năng quản lý thời gian và chịu đựng áp lực phòng thi tốt hơn.</p>
                        @elseif($hasWritingOrSpeakingWeak && $hasReadingOrListeningWeak)
                            <p>Kết quả cho thấy bạn cần cải thiện điểm số ở cả nhóm kỹ năng làm bài trắc nghiệm (Reading/Listening) lẫn kỹ năng chủ động (Writing/Speaking). Khuyên bạn nên chia nhỏ thời gian học: ưu tiên ôn luyện từ vựng và cấu trúc ngữ pháp thông qua Reading/Listening trước, sau đó áp dụng trực tiếp vào các đề viết và nói ngắn tại mục <strong>Practice</strong>.</p>
                        @elseif($hasWritingOrSpeakingWeak)
                            <p>Các kỹ năng chủ động (Writing/Speaking) của bạn hiện đang có điểm số thấp hơn các kỹ năng nghe đọc. Đây là điều thường gặp đối với người tự học. Hãy tận dụng tối đa tính năng <strong>Chấm điểm tự động bằng AI</strong> khi làm bài nói và viết để nhận được nhận xét chi tiết ngay lập tức, hoặc gửi bài cho giảng viên để nhận được sửa lỗi thủ công chuẩn xác.</p>
                        @elseif($hasReadingOrListeningWeak)
                            <p>Kỹ năng Nghe hoặc Đọc của bạn cần được cải thiện thêm. Hãy dành tối thiểu 30 phút mỗi ngày để luyện tập các bài lẻ tại mục <strong>Practice</strong>. Tập trung rèn luyện các kỹ năng đọc lướt (Skimming), quét thông tin (Scanning) và nhận diện các từ đồng nghĩa (Paraphrasing) để làm bài nhanh và chuẩn xác hơn.</p>
                        @else
                            <p>Bạn đang có tiến trình học tập rất tốt! Để bứt phá lên các điểm cao hơn (70+/100), hãy tập trung nghiên cứu kỹ các lỗi sai của mình thông qua tính năng <strong>Xem lại chi tiết bài làm</strong> lịch sử phía dưới. Việc hiểu rõ tại sao mình chọn sai sẽ giúp bạn tiến bộ nhanh gấp đôi.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- HISTORY LIST -->
        <div class="history-section">
            <div class="history-title">
                <i class="fa-solid fa-clock-rotate-left"></i>
                Lịch Sử Làm Bài
            </div>

            @if($results->isEmpty())
                <div class="empty-history">
                    <i class="fa-regular fa-clipboard"></i>
                    <p>Bạn chưa thực hiện bài làm nào. Hãy làm bài luyện tập đầu tiên nhé!</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Tên đề thi</th>
                                <th>Kỹ năng</th>
                                <th>Thời gian làm</th>
                                <th>Kết quả / Điểm</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $r)
                                <tr>
                                    <td style="font-weight: 600;">{{ $r->exam->title }}</td>
                                    <td>
                                        <span class="skill-badge {{ strtolower($r->exam->skill ?? 'general') }}">
                                            @if(($r->exam->skill ?? '') === 'reading')
                                                <i class="fa-solid fa-book-open"></i> Đọc
                                            @elseif(($r->exam->skill ?? '') === 'listening')
                                                <i class="fa-solid fa-headphones"></i> Nghe
                                            @elseif(($r->exam->skill ?? '') === 'writing')
                                                <i class="fa-solid fa-pen-nib"></i> Viết
                                            @elseif(($r->exam->skill ?? '') === 'speaking')
                                                <i class="fa-solid fa-microphone"></i> Nói
                                            @else
                                                Tổng hợp
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <strong style="font-size: 1.1rem; color: #3e2723;">
                                            @if($r->status === 'pending')
                                                --
                                            @else
                                                {{ $r->score }}/100
                                            @endif
                                        </strong>
                                        @if(in_array($r->exam->skill, ['reading', 'listening']) && $r->correct_answers !== null)
                                            <span style="font-size: 0.85rem; color: #8d6e63; margin-left: 5px;">
                                                ({{ $r->correct_answers }}/{{ $r->total_questions }})
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $r->status }}">
                                            {{ $r->status === 'graded' ? 'Đã chấm' : 'Chờ chấm' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('learning-results.show', $r) }}" class="btn-review">
                                            <i class="fa-solid fa-magnifying-glass"></i> Xem lại
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    {{ $results->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
