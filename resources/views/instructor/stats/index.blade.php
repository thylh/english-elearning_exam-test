<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Thống kê - English For You</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    @vite([
        'resources/css/app.css',
        'resources/css/english-for-you.css',
        'resources/css/english-for-you/stats.css',
        'resources/js/english-for-you.js'
    ])


</head>

<body class="page-transition">
    @include('partials.dashboard-header')

    <main class="stats-shell">
        <section class="stats-hero">
            <div>
                <h1>Thống kê</h1>
                <p>
                    Tổng quan hoạt động học tập, mức độ sử dụng đề thi và hiệu suất theo từng kỹ năng cho giảng viên.
                </p>
            </div>

            {{-- <div class="hero-badges">
                {{-- <div class="hero-badge">
                    <i class="fa-solid fa-clock"></i>
                    Bài chờ chấm: <strong>{{ number_format($pendingManualSubmissions) }}</strong>
                </div>
                <div class="hero-badge">
                    <i class="fa-solid fa-star"></i>
                    Điểm TB hệ thống:
                    <strong>
                        {{ $overallAverageScore !== null ? number_format($overallAverageScore, 1) . '/100' : '—' }}
                    </strong>
                </div>
            </div> --}}
        </section>

        {{-- <div class="alert-strip">
            <div>
                <strong>{{ number_format($pendingManualSubmissions) }}</strong> bài nộp manual đang chờ chấm.
            </div>
            {{-- <div>
                Cập nhật dựa trên dữ liệu `results` và `submissions` trong 30 ngày gần nhất.
            </div>
        </div> --}}

        <section class="kpi-grid">
            <article class="kpi-card" style="--accent:#2563eb; --accent-soft:#eff6ff;">
                <div class="kpi-top">
                    <div>
                        <div class="kpi-label">Tổng học viên</div>
                        <div class="kpi-value">{{ number_format($totalStudents) }}</div>
                    </div>
                    <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
                </div>
                {{-- <div class="kpi-foot">Chỉ tính tài khoản có role `student`.</div> --}}
            </article>

            <article class="kpi-card" style="--accent:#f59e0b; --accent-soft:#fffbeb;">
                <div class="kpi-top">
                    <div>
                        <div class="kpi-label">Tổng bài thi</div>
                        <div class="kpi-value">{{ number_format($totalExams) }}</div>
                    </div>
                    <div class="kpi-icon"><i class="fa-solid fa-clipboard-list"></i></div>
                </div>
                {{-- <div class="kpi-foot">Gồm toàn bộ đề thi hiện có trong hệ thống.</div> --}}
            </article>

            <article class="kpi-card" style="--accent:#10b981; --accent-soft:#ecfdf5;">
                <div class="kpi-top">
                    <div>
                        <div class="kpi-label">Tổng lượt làm bài</div>
                        <div class="kpi-value">{{ number_format($totalAttempts) }}</div>
                    </div>
                    <div class="kpi-icon"><i class="fa-solid fa-file-circle-check"></i></div>
                </div>
                {{-- <div class="kpi-foot">Tổng số bản ghi trong bảng `results`.</div> --}}
            </article>

            <article class="kpi-card" style="--accent:#ec4899; --accent-soft:#fdf2f8;">
                <div class="kpi-top">
                    <div>
                        <div class="kpi-label">Điểm TB hệ thống</div>
                        <div class="kpi-value">
                            {{ $overallAverageScore !== null ? number_format($overallAverageScore, 1) . '/100' : '—' }}
                        </div>
                    </div>
                    <div class="kpi-icon"><i class="fa-solid fa-chart-line"></i></div>
                </div>
                {{-- <div class="kpi-foot">Chỉ tính các kết quả đã chấm (`graded`).</div> --}}
            </article>
        </section>

        <section class="section-grid">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h2 class="panel-title">
                            <i class="fa-solid fa-layer-group"></i>
                            Thống kê theo kỹ năng
                        </h2>
                        <p class="panel-subtitle">Điểm trung bình và số lượt làm bài theo 4 kỹ năng.</p>
                    </div>
                </div>

                <div class="skill-list">
                    @foreach($skillStats as $skill)
                        <article class="skill-card"
                            style="--skill-color: {{ $skill['color'] }}; --skill-light: {{ $skill['light'] }};">
                            <div class="skill-icon"><i class="fa-solid {{ $skill['icon'] }}"></i></div>
                            <div>
                                <div class="skill-row">
                                    <div class="skill-name">{{ $skill['label'] }}</div>
                                    <div class="skill-score">
                                        {{ $skill['avg_score'] !== null ? number_format($skill['avg_score'], 1) . '/100' : '—' }}
                                    </div>
                                </div>
                                <div class="skill-bar">
                                    <div class="skill-bar-fill"
                                        data-width="{{ $skill['avg_score'] !== null ? min(100, max(0, $skill['avg_score'])) : 0 }}">
                                    </div>
                                </div>
                                <div class="skill-meta">{{ number_format($skill['attempts']) }} lượt làm bài</div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h2 class="panel-title">
                            <i class="fa-solid fa-trophy"></i>
                            Top học viên
                        </h2>
                        <p class="panel-subtitle">Top 5 học viên có điểm trung bình cao nhất trong các kết quả graded.
                        </p>
                    </div>
                </div>

                @if($topStudents->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-user-slash"></i>
                        <div>Chưa có dữ liệu chấm điểm.</div>
                    </div>
                @else
                    <table class="rank-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Học viên</th>
                                <th>Lượt</th>
                                <th>Điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topStudents as $index => $student)
                                @php
                                    $rank = $index + 1;
                                    $score = (float) $student->avg_score;
                                    $scoreClass = $score >= 80 ? 'high' : ($score >= 65 ? 'mid' : 'low');
                                    $initial = mb_strtoupper(mb_substr($student->name, 0, 1));
                                @endphp
                                <tr>
                                    <td class="rank-cell">
                                        <span class="rank-pill">{{ $rank }}</span>
                                    </td>
                                    <td>
                                        <div class="student-row">
                                            <div class="student-avatar">{{ $initial }}</div>
                                            <div>
                                                <div class="student-name">{{ $student->name }}</div>
                                                <div class="student-email">{{ $student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($student->attempts) }}</td>
                                    <td><span class="score-chip {{ $scoreClass }}">{{ number_format($score, 1) }}/100</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </section>

        <section class="bottom-grid">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h2 class="panel-title">
                            <i class="fa-solid fa-chart-column"></i>
                            Hoạt động 30 ngày gần nhất
                        </h2>
                        <p class="panel-subtitle">Số bài nộp được ghi nhận theo ngày trong 30 ngày gần đây.</p>
                    </div>
                </div>

                <div class="chart-wrap">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h2 class="panel-title">
                            <i class="fa-solid fa-fire"></i>
                            Bài thi được làm nhiều nhất
                        </h2>
                        <p class="panel-subtitle">Top 10 theo số lượt làm bài.</p>
                    </div>
                </div>

                @if($topExams->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-inbox"></i>
                        <div>Chưa có dữ liệu bài thi.</div>
                    </div>
                @else
                    <div class="exam-list">
                        @foreach($topExams as $index => $exam)
                            @php
                                $skill = strtolower($exam->skill ?? 'general');
                                $skillIcon = [
                                    'reading' => 'fa-book-open',
                                    'listening' => 'fa-headphones',
                                    'writing' => 'fa-pen-nib',
                                    'speaking' => 'fa-microphone',
                                ][$skill] ?? 'fa-clipboard-list';
                            @endphp
                            <article class="exam-item">
                                <div class="exam-rank">{{ $index + 1 }}</div>
                                <div>
                                    <div class="exam-title" title="{{ $exam->title }}">{{ $exam->title }}</div>
                                    <div class="exam-meta">
                                        <span class="skill-pill {{ $skill }}">
                                            <i class="fa-solid {{ $skillIcon }}"></i>
                                            {{ ucfirst($skill) }}
                                        </span>
                                        @if($exam->avg_score !== null)
                                            <span>Điểm TB: {{ number_format($exam->avg_score, 1) }}/100</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="exam-count">{{ number_format($exam->attempts) }} lượt</div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </main>

    <script>
        const activityLabels = @json($activityLabels);
        const activityData = @json($activityData);

        document.querySelectorAll('.skill-bar-fill').forEach((bar) => {
            const width = Number(bar.dataset.width || 0);
            requestAnimationFrame(() => {
                bar.style.width = `${width}%`;
            });
        });

        const canvas = document.getElementById('activityChart');

        if (canvas) {
            const chart = new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: activityLabels,
                    datasets: [{
                        label: 'Lượt nộp',
                        data: activityData,
                        borderWidth: 1,
                        borderRadius: 8,
                        backgroundColor: 'rgba(59, 130, 246, 0.16)',
                        borderColor: '#2563eb',
                        hoverBackgroundColor: 'rgba(59, 130, 246, 0.28)',
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            backgroundColor: '#2b1d17',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            displayColors: false,
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: '#8b6b5b',
                                maxRotation: 0,
                                autoSkip: false,
                                callback: (value, index) => (index % 5 === 0 ? activityLabels[index] : ''),
                            },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: '#8b6b5b',
                            },
                            grid: {
                                color: 'rgba(147, 99, 75, 0.12)',
                            },
                        },
                    },
                },
            });
        }
    </script>
</body>

</html>
