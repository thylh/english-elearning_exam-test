<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Test</title>

    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="practice-layout">
        <!-- ================= SIDEBAR ================= -->
        <aside class="practice-sidebar">
            <div class="filter-icon">
                <i class="fa-solid fa-filter"></i>
            </div>

            <div class="promo-card">
                <h3>
                    <i class="fa-solid fa-clipboard-list"></i>
                    Exam Library
                </h3>
                <p>Làm các bài thi thực tế đã được publish.</p>
            </div>
        </aside>

        <!-- ================= CONTENT ================= -->
        <main class="practice-content">
            <div class="practice-grid">
                @forelse($exams as $exam)
                    <div class="practice-card highlight-card">
                        <div class="card-tag">Full Exam</div>
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=1200">
                        <div class="card-content">
                            <div class="band-tag">{{ $exam->band ? 'Band ' . $exam->band : 'Test' }}</div>
                            <h3>{{ $exam->title }}</h3>
                            <p>{{ Str::limit($exam->description ?? 'Bài thi hoàn chỉnh', 120) }}</p>
                            <a href="{{ route('exams.take', ['exam' => $exam, 'origin' => 'exam-test']) }}">Làm bài</a>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; padding: 40px; text-align: center;">
                        <p style="font-size: 16px; color: #666;">Chưa có đề thi nào được publish.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</body>

</html>
