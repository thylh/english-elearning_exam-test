<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Writing IELTS</title>

    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])

</head>

<body>

    <div class="writing-layout">


        <!-- LEFT -->
        <div class="writing-left">

            <div class="question-box-writing">

                <p>

                    The table below shows the change
                    in number of people engaged in
                    various physical activities
                    between the years 2001-2009
                    in Australia.

                </p>


            </div>

            <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=1200">

            <!-- GUIDE -->
            <div class="writing-guide">

                <h1>
                    HƯỚNG DẪN VIẾT BÀI
                </h1>

                <h3>

                    IELTS Writing Task 1

                </h3>

                <p>

                    Đề bài yêu cầu mô tả sự thay đổi
                    của số lượng người tham gia
                    hoạt động thể chất tại Úc.

                </p>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="writing-right">

            <div class="writing-header">
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <span class="word-count">Word count: 0</span>
                    <div id="timer"></div>
                </div>
            </div>

            <!-- INTRO -->
            <div class="writing-section">

                <h3>
                    Introduction
                </h3>

                <textarea placeholder="Nhập phần viết của bạn ở đây"></textarea>

            </div>

            <!-- OVERVIEW -->
            <div class="writing-section">

                <h3>
                    Overview
                </h3>

                <textarea placeholder="Nhập phần viết của bạn ở đây"></textarea>

            </div>

            <!-- BODY -->
            <div class="writing-section">

                <h3>
                    Body 1
                </h3>

                <textarea placeholder="Nhập phần viết của bạn ở đây"></textarea>

            </div>

            <!-- FOOTER -->
            <div class="writing-footer">

                <div class="time-box">

                    <span>
                        Thời gian:
                    </span>

                    <h2>
                        00:00:19
                    </h2>

                </div>

                <button id="submit-btn" class="score-btn">Nộp bài</button>

            </div>

        </div>

    </div>

</body>

</html>

<script>
    (function () {
        const duration = {{ $exam->duration_minutes ?? 30 }} * 60;
        let remaining = duration;
        const timerEl = document.getElementById('timer');
        const submitBtn = document.getElementById('submit-btn');

        function format(ms) {
            const m = Math.floor(ms / 60).toString().padStart(2, '0');
            const s = (ms % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        timerEl.textContent = format(remaining);
        const iv = setInterval(() => {
            remaining--;
            timerEl.textContent = format(remaining);
            if (remaining <= 0) {
                clearInterval(iv);
                document.getElementById('submit-btn').click();
            }
        }, 1000);

        submitBtn.addEventListener('click', function () {
            // For writing, submit the textarea content as answer for the first question id if exists
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('exams.submit', $exam) }}`;
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'answers';
            const data = {};
            // collect any textarea fields inside writing-left/right
            document.querySelectorAll('textarea').forEach((ta, idx) => {
                const qid = ta.getAttribute('data-qid') || ('w' + idx);
                data[qid] = ta.value;
            });
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'answers';
            hidden.value = JSON.stringify(data);
            form.appendChild(hidden);
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';
            form.appendChild(token);
            document.body.appendChild(form);
            form.submit();
        });
    })();
</script>