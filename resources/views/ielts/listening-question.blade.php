<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Listening</title>

    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

@php
    $promptText = $questions->first()?->prompt_text;
    $examAudioUrl = null;
    $examAudioType = 'audio/mpeg';
    $audioExtension = null;

    if (!empty($exam->audio_url)) {
        $examAudioUrl = preg_match('/^https?:\/\//i', $exam->audio_url) ? $exam->audio_url : asset($exam->audio_url);
        $audioExtension = strtolower(pathinfo($exam->audio_url, PATHINFO_EXTENSION));
    } else {
        foreach ($questions as $q) {
            if ($q->prompt_attachment) {
                $ext = strtolower(pathinfo($q->prompt_attachment, PATHINFO_EXTENSION));
                if (in_array($ext, ['mp3', 'wav', 'm4a', 'aac', 'ogg'], true)) {
                    // Serve audio via protected media route (private disk)
                    $examAudioUrl = route('media.exam_question', $q);
                    $audioExtension = $ext;
                    $firstAudioQuestionId = $q->id;
                    break;
                }
            }
        }
    }

    if ($examAudioUrl && $audioExtension) {
        $examAudioType = 'audio/' . ($audioExtension === 'mp3' ? 'mpeg' : $audioExtension);
    }
@endphp

<body>

    <div class="reading-page">

        <main class="practice-content">

            <section class="reading-test" id="listening-test">

                <div class="reading-left">

                    <h1>Listening Audio</h1>

                    @if($examAudioUrl)
                        <div class="listening-audio-block" style="margin:30px 0;">
                            <audio id="main-listening-audio" controls preload="metadata" style="width:100%;">
                                <source src="{{ $examAudioUrl }}" type="{{ $examAudioType }}">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    @else
                        <div class="alert alert-warning">Không có file nghe cho bài này.</div>
                    @endif

                    @if($promptText)
                        <div class="listening-prompt">{!! nl2br(e($promptText)) !!}</div>
                    @else
                        <p>Listen carefully and choose the correct answer.</p>
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

                                    @if(!empty($q->options))
                                        @if(in_array($q->question_type, ['checkbox', 'multiple_choice']))
                                            @foreach($q->options as $opt)
                                                <label><input type="checkbox" name="answers[{{ $q->id }}][]" value="{{ $opt }}">
                                                    {{ $opt }}</label>
                                            @endforeach
                                        @else
                                            @foreach($q->options as $opt)
                                                <label><input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}">
                                                    {{ $opt }}</label>
                                            @endforeach
                                        @endif
                                    @else
                                        <p class="text-muted">Câu hỏi này cần đáp án trắc nghiệm nhưng chưa có lựa chọn.</p>
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
        const returnOrigin = '{{ $returnTarget ?? 'practice' }}';
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
                const checkbox = block.querySelector('input[type="checkbox"]');
                const radio = block.querySelector('input[type="radio"]');
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
                body: JSON.stringify({ answers, origin: returnOrigin })
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
            listEl.style.transform = `translateX(${-(itemWidth + gap) * currentIndex}px)`;
            prevBtn.disabled = currentIndex <= 0;
            nextBtn.disabled = currentIndex >= Math.max(0, questionItems.length - pageSize);
        }

        questionItems.forEach((item) => {
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

        const mainAudio = document.getElementById('main-listening-audio');
        const audioError = document.getElementById('audio-load-error');
        const audioButton = document.getElementById('listening-play-btn');
        const audioStatus = document.getElementById('listening-play-status');

        if (mainAudio) {
            mainAudio.addEventListener('canplay', () => {
                if (audioStatus) audioStatus.textContent = 'Sẵn sàng';
            });

            mainAudio.addEventListener('play', () => {
                if (audioButton) audioButton.textContent = 'Tạm dừng';
                if (audioStatus) audioStatus.textContent = 'Đang phát';
            });

            mainAudio.addEventListener('pause', () => {
                if (audioButton) audioButton.textContent = 'Phát';
                if (audioStatus) audioStatus.textContent = 'Tạm dừng';
            });

            mainAudio.addEventListener('ended', () => {
                if (audioButton) audioButton.textContent = 'Phát lại';
                if (audioStatus) audioStatus.textContent = 'Hoàn thành';
            });

            mainAudio.addEventListener('error', () => {
                if (audioError) audioError.style.display = 'block';
                if (audioStatus) audioStatus.textContent = 'Lỗi phát audio';
            });

            if (audioButton) {
                audioButton.addEventListener('click', () => {
                    if (mainAudio.paused) {
                        mainAudio.play().catch(() => {
                            if (audioError) audioError.style.display = 'block';
                            if (audioStatus) audioStatus.textContent = 'Không thể phát';
                        });
                    } else {
                        mainAudio.pause();
                    }
                });
            }
        }

        if (questionItems[0]) {
            questionItems[0].classList.add('active');
        }

        updateQuestionNav();
    })();
</script>
