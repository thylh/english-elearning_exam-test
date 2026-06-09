<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reading IELTS</title>

    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

@php
    $promptText = $questions->first()?->prompt_text;
@endphp

<body>

    <div class="reading-page">



        <!-- ================= CONTENT ================= -->

        <main class="practice-content">

            <section class="reading-test">

                <div class="reading-left">
                    @if($promptText)
                        <h3>{{ $promptText }}
                            {{-- {!! nl2br(e($promptText)) !!} --}}
                        </h3>
                    @else
                        <h1>Early Approaches to Organisational Design</h1>
                        <p>Determining the best type of organisational structure for a particular situation...</p>
                        <p>The Classical Approach</p>
                        <p>Early management writers attempted to approach organisational design...</p>
                    @endif
                </div>

                <div class="reading-right">

                    <div class="reading-top">
                        <div class="reading-timer" id="timer"></div>
                        <div class="top-buttons">
                            <button type="button" id="submit-btn" class="submit-btn">Nộp bài</button>
                        </div>
                    </div>

                    <div class="reading-question">
                        <form id="exam-form">
                            @foreach($questions as $q)
                                <div class="q-block" data-qid="{{ $q->id }}">
                                    <h2>Question {{ $loop->iteration }}</h2>
                                    <p>{!! nl2br(e($q->question_text)) !!}</p>

                                    @if($q->prompt_attachment)
                                        @php
                                            $attachmentExt = strtolower(pathinfo($q->prompt_attachment, PATHINFO_EXTENSION));
                                            $attachmentUrl = route('media.exam_question', $q);
                                        @endphp
                                        <div class="question-attachment mb-3">
                                            @if(in_array($attachmentExt, ['mp3', 'wav', 'm4a', 'aac', 'ogg']))
                                                <audio controls style="width: 100%;">
                                                    <source src="{{ $attachmentUrl }}"
                                                        type="audio/{{ $attachmentExt === 'mp3' ? 'mpeg' : $attachmentExt }}">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            @elseif(in_array($attachmentExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <img src="{{ $attachmentUrl }}" alt="Attachment for question {{ $loop->iteration }}"
                                                    style="max-width: 100%; display: block; margin-top: 1rem;">
                                            @else
                                                <a href="{{ $attachmentUrl }}" target="_blank" rel="noopener">Tải xuống tệp đính
                                                    kèm</a>
                                            @endif
                                        </div>
                                    @endif

                                    @if($q->question_type === 'checkbox')
                                        @foreach($q->options ?? [] as $idx => $opt)
                                            <label>
                                                <input type="checkbox" name="answers[{{ $q->id }}][]" value="{{ $opt }}"> {{ $opt }}
                                            </label>
                                        @endforeach
                                    @elseif($q->question_type === 'multiple_choice')
                                        @foreach($q->options ?? [] as $idx => $opt)
                                            <label>
                                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}"> {{ $opt }}
                                            </label>
                                        @endforeach
                                    @else
                                        <textarea name="answers[{{ $q->id }}]" placeholder="Your answer"></textarea>
                                    @endif
                                </div>
                            @endforeach
                        </form>
                    </div>

                    <div class="question-number-controls">
                        <button type="button" class="nav-arrow" id="question-prev">‹</button>
                        <div class="question-number-list">
                            @foreach($questions as $q)
                                <button type="button" class="question-number-item"
                                    data-qid="{{ $q->id }}">{{ $loop->iteration }}</button>
                            @endforeach
                        </div>
                        <button type="button" class="nav-arrow" id="question-next">›</button>
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
        const isPractice = '{{ $exam->type ?? 'exam' }}' === 'practice';
        let elapsed = 0;
        let remaining = duration;
        const timerEl = document.getElementById('timer');
        const submitBtn = document.getElementById('submit-btn');
        const examId = '{{ $exam->id }}';
        const examSkill = '{{ $exam->skill ?? 'general' }}';
        const storageKey = `exam_draft_${examId}_${examSkill}`;
        const timeKey = `${storageKey}_time`;
        const navigationType = performance.getEntriesByType?.('navigation')?.[0]?.type ||
            (performance.navigation ? (performance.navigation.type === 1 ? 'reload' : performance.navigation.type === 2 ? 'back_forward' : 'navigate') : null);
        const isReload = navigationType === 'reload';
        let ignoreBeforeUnload = false;
        const form = document.getElementById('exam-form');

        function format(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        function updateTimer() {
            timerEl.textContent = isPractice ? format(elapsed) : format(Math.max(0, remaining));
        }

        function collectAnswers() {
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

        function saveAnswers() {
            try {
                localStorage.setItem(storageKey, JSON.stringify(collectAnswers()));
            } catch (e) {
                // ignore storage errors
            }
        }

        function restoreAnswers() {
            try {
                if (!isReload) {
                    clearDraft();
                    return;
                }

                const saved = localStorage.getItem(storageKey);
                if (!saved) return;
                const answers = JSON.parse(saved);
                for (const qid in answers) {
                    const value = answers[qid];
                    const textArea = form.querySelector(`textarea[name="answers[${qid}]"]`);
                    const radios = form.querySelectorAll(`input[name="answers[${qid}]"]`);
                    const checkboxes = form.querySelectorAll(`input[name="answers[${qid}][]"]`);

                    if (textArea && typeof value === 'string') {
                        textArea.value = value;
                    }

                    if (checkboxes.length && Array.isArray(value)) {
                        checkboxes.forEach(input => {
                            input.checked = value.includes(input.value);
                        });
                    }

                    if (radios.length && typeof value === 'string') {
                        radios.forEach(input => {
                            input.checked = input.value === value;
                        });
                    }
                }
            } catch (e) {
                // ignore storage errors
            }
        }

        function saveTime() {
            try {
                const timeValue = isPractice ? elapsed : remaining;
                localStorage.setItem(timeKey, String(timeValue));
            } catch (e) {
                // ignore storage errors
            }
        }

        function restoreTime() {
            try {
                const saved = localStorage.getItem(timeKey);
                if (!saved) return;
                const value = parseInt(saved, 10);
                if (isNaN(value)) return;
                if (isPractice) {
                    elapsed = value;
                } else {
                    remaining = value;
                }
            } catch (e) {
                // ignore storage errors
            }
        }

        function clearDraft() {
            try {
                localStorage.removeItem(storageKey);
                localStorage.removeItem(timeKey);
            } catch (e) {
                // ignore storage errors
            }
        }

        form.addEventListener('input', saveAnswers);
        form.addEventListener('change', saveAnswers);
        restoreAnswers();
        restoreTime();
        updateTimer();

        window.addEventListener('keydown', (event) => {
            if (event.key === 'F5' || ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'r')) {
                ignoreBeforeUnload = true;
            }
        });

        window.addEventListener('keyup', () => {
            ignoreBeforeUnload = false;
        });

        window.addEventListener('beforeunload', (event) => {
            if (ignoreBeforeUnload) return;
            if (Object.keys(JSON.parse(localStorage.getItem(storageKey) || '{}')).length > 0) {
                event.preventDefault();
                event.returnValue = '';
            }
        });

        window.addEventListener('pageshow', (event) => {
            if (event.persisted && !isReload) {
                clearDraft();
            }
        });

        const iv = setInterval(() => {
            if (isPractice) {
                elapsed += 1;
                updateTimer();
                saveTime();
                if (elapsed >= duration) {
                    clearInterval(iv);
                    window.alert('Thời gian practice đã hết. Bài sẽ tự động nộp.');
                    submit();
                }
            } else {
                remaining -= 1;
                updateTimer();
                saveTime();
                if (remaining <= 0) {
                    clearInterval(iv);
                    window.alert('Hết giờ! Bài sẽ tự động nộp.');
                    submit();
                }
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

        function findUnansweredQuestions() {
            const unanswered = [];
            const blocks = document.querySelectorAll('.q-block');
            blocks.forEach((block, index) => {
                const qid = block.dataset.qid;
                const radio = block.querySelector('input[type="radio"]');
                const checkbox = block.querySelector('input[type="checkbox"]');
                const textarea = block.querySelector('textarea');
                let answered = false;

                if (checkbox) {
                    answered = Array.from(block.querySelectorAll('input[type="checkbox"]')).some(input => input.checked);
                } else if (radio) {
                    answered = Array.from(block.querySelectorAll('input[type="radio"]')).some(input => input.checked);
                } else if (textarea) {
                    answered = textarea.value.trim().length > 0;
                }

                if (!answered) {
                    unanswered.push(index + 1);
                }
            });
            return unanswered;
        }

        async function submit() {
            ignoreBeforeUnload = true;
            const unanswered = findUnansweredQuestions();
            if (unanswered.length > 0) {
                const message = `Bạn chưa làm câu ${unanswered.join(', ')}. Có chắc muốn nộp bài?`;
                if (!window.confirm(message)) {
                    submitBtn.disabled = false;
                    ignoreBeforeUnload = false;
                    return;
                }
            }

            submitBtn.disabled = true;
            clearDraft();
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

        const questionItems = Array.from(document.querySelectorAll('.question-number-item'));
        const listEl = document.querySelector('.question-number-list');
        const prevBtn = document.getElementById('question-prev');
        const nextBtn = document.getElementById('question-next');
        const pageSize = 5;
        let currentIndex = 0;

        function updateQuestionNav() {
            const itemWidth = questionItems[0]?.getBoundingClientRect().width ?? 62;
            const gap = 12;
            const shift = -(itemWidth + gap) * currentIndex;
            listEl.style.transform = `translateX(${shift}px)`;
            prevBtn.disabled = currentIndex <= 0;
            nextBtn.disabled = currentIndex >= Math.max(0, questionItems.length - pageSize);
        }

        questionItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                document.querySelector(`.q-block[data-qid="${item.dataset.qid}"]`)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                questionItems.forEach(btn => btn.classList.remove('active'));
                item.classList.add('active');
            });
        });

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex -= 1;
                updateQuestionNav();
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentIndex < questionItems.length - pageSize) {
                currentIndex += 1;
                updateQuestionNav();
            }
        });

        if (questionItems.length <= pageSize) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
        }

        if (questionItems[0]) {
            questionItems[0].classList.add('active');
        }

        updateQuestionNav();
    })();
</script>