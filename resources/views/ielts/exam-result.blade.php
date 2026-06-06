<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Exam Result</title>
    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])
</head>

<body>
    <div class="practice-layout">
        <main class="practice-content">
            <div style="padding:24px">
                <h1>Kết quả: {{ $exam->title }}</h1>
                <p>Score: <strong>{{ $score }}%</strong> ({{ $correct }} / {{ $total }})</p>

                <h3>Chi tiết</h3>
                <ul>
                    @foreach($details as $d)
                        <li>Question {{ $d['question_id'] }}: @if($d['correct']) <strong
                        style="color:green">Correct</strong> @else <strong style="color:red">Wrong</strong> @endif
                        </li>
                    @endforeach
                </ul>

                <p><a href="/practice">Quay lại Practice</a> · <a href="/exam-test">Xem đề khác</a></p>
            </div>
        </main>
    </div>
</body>

</html>