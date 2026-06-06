<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Speaking IELTS</title>

    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])

</head>

<body>

    <div class="reading-page">


        <main class="practice-content">

            <section class="reading-test" id="speaking-test">

                <div class="reading-left">

                    <h1>Speaking Part 2</h1>

                    <p>Describe a person who inspired you.</p>

                    <ul>
                        <li>Who the person is</li>
                        <li>Why you admire them</li>
                        <li>How they inspired you</li>
                    </ul>

                </div>

                <div class="reading-right">

                    <div class="reading-top">
                        <div class="reading-timer" id="timer"></div>
                    </div>

                    <div class="reading-question">
                        <form id="exam-form">
                            @foreach($questions as $q)
                                <div class="q-block" data-qid="{{ $q->id }}">
                                    <h4>Question {{ $q->part_number ?? $loop->iteration }}</h4>
                                    <p>{!! nl2br(e($q->question_text)) !!}</p>
                                    @if($q->question_type === 'multiple')
                                        @foreach($q->options ?? [] as $opt)
                                            <label><input type="checkbox" name="answers[{{ $q->id }}][]" value="{{ $opt }}">
                                                {{ $opt }}</label>
                                        @endforeach
                                    @elseif($q->question_type === 'single')
                                        @foreach($q->options ?? [] as $opt)
                                            <label><input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}">
                                                {{ $opt }}</label>
                                        @endforeach
                                    @else
                                        <textarea name="answers[{{ $q->id }}]" placeholder="Your answer"></textarea>
                                    @endif
                                </div>
                            @endforeach

                            <div style="margin-top:16px">
                                <button type="button" id="submit-btn">Nộp bài</button>
                            </div>
                        </form>
                    </div>

                </div>

            </section>

        </main>

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
                submit();
            }
        }, 1000);

        function collectAnswers() {
            const form = document.getElementById('exam-form');
            const fd = new FormData(form);
            const answers = {};
            for (const [k, v] of fd.entries()) {
                const m = k.match(/answers\[(\d+)\]/);
                if (!m) continue;
                const qid = m[1];
                if (k.endsWith('[]')) {
                    if (!answers[qid]) answers[qid] = [];
                    answers[qid].push(v);
                } else {
                    if (answers[qid]) {
                        if (Array.isArray(answers[qid])) answers[qid].push(v);
                        else answers[qid] = [answers[qid], v];
                    } else answers[qid] = v;
                }
            }
            return answers;
        }

        async function submit() {
            submitBtn.disabled = true;
            const answers = collectAnswers();
            const res = await fetch(`{{ route('exams.submit', $exam) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ answers })
            });
            if (res.redirected) window.location = res.url;
            else {
                const text = await res.text();
                document.body.innerHTML = text;
            }
        }

        submitBtn.addEventListener('click', submit);
    })();
</script>