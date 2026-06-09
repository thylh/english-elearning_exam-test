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

    <form id="writing-form" method="POST" action="{{ route('exams.submit', $exam) }}">
        @csrf
        <div class="writing-layout">


            <!-- LEFT -->
            <div class="writing-left">

                <div class="question-box-writing">
                    <p>
                        {!! nl2br(e($questions->first()->prompt_text ?? $questions->first()->question_text ?? 'No question provided.')) !!}
                    </p>
                </div>

                @if(optional($questions->first())->prompt_attachment)
                    @php
                        $attachmentUrl = route('media.exam_question', $questions->first());
                        $attachmentExt = strtolower(pathinfo($questions->first()->prompt_attachment, PATHINFO_EXTENSION));
                    @endphp
                    <div class="question-attachment">
                        @if(in_array($attachmentExt, ['mp3', 'wav', 'm4a', 'aac', 'ogg']))
                            <audio controls src="{{ $attachmentUrl }}"></audio>
                        @elseif(in_array($attachmentExt, ['mp4', 'webm', 'ogg']))
                            <video controls src="{{ $attachmentUrl }}" style="max-width:100%;"></video>
                        @elseif(in_array($attachmentExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            <img src="{{ $attachmentUrl }}" alt="Attachment for question">
                        @else
                            <a href="{{ $attachmentUrl }}" target="_blank" rel="noopener">Tải file đính kèm</a>
                        @endif
                    </div>
                @endif

                <div class="writing-guide">
                    <h1 style="text-align: start;">Question:</h1>
                    <p>{!! nl2br(e($questions->first()->question_text ?? '')) !!}</p>
                </div>

            </div>

            <!-- RIGHT -->
            <div class="writing-right">

                <div class="writing-header">
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span class="word-count">Word count: 0</span>
                    </div>
                </div>

                <input type="hidden" name="grading_method" id="grading-method" value="manual">

                <div class="writing-section">
                    <h3>
                        Viết bài trả lời
                    </h3>
                    <textarea id="writing-answer" data-qid="{{ $questions->first()->id ?? 0 }}"
                        placeholder="Nhập phần viết của bạn ở đây" rows="30"
                        style="min-height:450px; max-height:calc(100vh - 260px); height:auto; line-height:1.6;"></textarea>
                </div>

                <!-- FOOTER -->
                <div class="writing-footer">

                    <div class="time-box">

                        <span>
                            Thời gian:
                        </span>

                        <h2 id="elapsed-time">
                            00:00
                        </h2>

                    </div>

                    <button id="submit-btn" class="score-btn" type="button">Nộp bài</button>

                </div>

            </div>

        </div>
    </form>

    <div id="grading-modal" class="grading-modal hidden">
        <div class="grading-modal__content">
            <div class="grading-modal__header">
                <h2>Chọn phương thức chấm</h2>
                <button id="grading-modal-close" type="button" class="grading-modal__close" aria-label="Đóng">×</button>
            </div>
            <p>Chọn cách chấm bài chính xác nhất cho bài viết trước khi nộp.</p>
            <div class="grading-modal__options">
                <div class="grading-modal__option">
                    <button id="manual-grade-btn" type="button" class="btn btn-primary">Chấm thủ công</button>
                    <p class="grading-modal__option-desc">Đánh giá sát thực lực, phù hợp khi bạn cần chấm chính xác từ
                        giáo viên.</p>
                </div>
                <div class="grading-modal__option">
                    <button id="auto-grade-btn" type="button" class="btn btn-secondary">Chấm tự động</button>
                    <p class="grading-modal__option-desc">Nhanh chóng và tự động, phù hợp khi cần điểm ngay lập tức.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .grading-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 16px;
        }

        .grading-modal.hidden {
            display: none;
        }

        .grading-modal__content {
            background: #f9fbff;
            border: 1px solid rgba(70, 96, 187, 0.12);
            border-radius: 20px;
            padding: 26px 24px 24px;
            max-width: 520px;
            width: 100%;
            box-shadow: 0 24px 60px rgba(23, 43, 77, 0.14);
            text-align: left;
            position: relative;
        }

        .grading-modal__header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .grading-modal__content h2 {
            margin: 0;
            font-size: 24px;
            line-height: 1.15;
            color: #1a2638;
        }

        .grading-modal__close {
            background: rgba(29, 53, 112, 0.05);
            border: 1px solid rgba(29, 53, 112, 0.12);
            border-radius: 999px;
            font-size: 22px;
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #1d3570;
            cursor: pointer;
            padding: 0;
        }

        .grading-modal__content p {
            margin: 0 0 22px;
            color: #4f5e78;
            font-size: 0.96rem;
            line-height: 1.6;
        }

        .grading-modal__options {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .grading-modal__option {
            display: flex;
            flex-direction: column;
            gap: 8px;
            background: #fff;
            border: 1px solid rgba(74, 105, 255, 0.12);
            border-radius: 16px;
            padding: 16px;
        }

        .grading-modal__option .btn {
            width: 100%;
            border-radius: 12px;
            padding: 12px 14px;
            cursor: pointer;

            box-shadow: 0 10px 24px rgba(74, 105, 255, 0.12);
        }

        #manual-grade-btn {
            background: #3047f0;
            border-color: #3047f0;
            color: #fff;
        }

        #manual-grade-btn:hover {
            background: #2536d0;
        }

        #auto-grade-btn {
            background: #eef2ff;
            border-color: #d2d6ff;
            color: #1f2d6f;
        }

        #auto-grade-btn:hover {
            background: #dde4ff;
        }

        .grading-modal__option-desc {
            margin: 0;
            color: #5f6d88;
            font-size: 0.92rem;
            line-height: 1.5;
        }

        @media (max-width: 560px) {
            .grading-modal__options {
                grid-template-columns: 1fr;
            }

            .grading-modal__content {
                padding: 22px;
            }
        }
    </style>

</body>

</html>

<script>
    (function () {
        const submitBtn = document.getElementById('submit-btn');
        const answerField = document.getElementById('writing-answer');
        const wordCountEl = document.querySelector('.word-count');
        const elapsedEl = document.getElementById('elapsed-time');
        const gradingMethodField = document.getElementById('grading-method');
        const form = document.getElementById('writing-form');
        const modal = document.getElementById('grading-modal');
        const closeModalBtn = document.getElementById('grading-modal-close');
        const manualGradeBtn = document.getElementById('manual-grade-btn');
        const autoGradeBtn = document.getElementById('auto-grade-btn');
        const qid = answerField.getAttribute('data-qid');
        const examId = '{{ $exam->id }}';
        const storageKey = `writing_answer_${examId}_${qid}`;
        const timeKey = `${storageKey}_time`;
        const navigationType = performance.getEntriesByType?.('navigation')?.[0]?.type ||
            (performance.navigation ? (performance.navigation.type === 1 ? 'reload' : performance.navigation.type === 2 ? 'back_forward' : 'navigate') : null);
        const isReload = navigationType === 'reload';

        let elapsedSeconds = 0;

        function format(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        function updateWordCount() {
            const text = answerField.value.trim();
            const count = text === '' ? 0 : text.split(/\s+/).filter(Boolean).length;
            wordCountEl.textContent = `Word count: ${count}`;
        }

        function saveDraft() {
            try {
                localStorage.setItem(storageKey, answerField.value);
            } catch (e) {
                // ignore storage errors
            }
        }

        function saveElapsedTime() {
            try {
                localStorage.setItem(timeKey, String(elapsedSeconds));
            } catch (e) {
                // ignore storage errors
            }
        }

        function updateAnswerInput() {
            const existing = form.querySelector('input[name="answers[' + qid + ']"]');
            if (existing) {
                existing.value = answerField.value;
            } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'answers[' + qid + ']';
                input.value = answerField.value;
                form.appendChild(input);
            }
        }

        function restoreDraft() {
            try {
                if (!isReload) {
                    clearDraft();
                    return;
                }

                const savedText = localStorage.getItem(storageKey);
                const savedTime = localStorage.getItem(timeKey);
                if (savedText !== null) {
                    answerField.value = savedText;
                }
                if (savedTime !== null && !isNaN(parseInt(savedTime, 10))) {
                    elapsedSeconds = parseInt(savedTime, 10);
                }
            } catch (e) {
                // ignore storage errors
            }
        }

        function shouldWarnOnExit() {
            return !ignoreBeforeUnload && (answerField.value.trim() !== '' || elapsedSeconds > 0);
        }

        function confirmBeforeUnload(event) {
            if (shouldWarnOnExit()) {
                event.preventDefault();
                event.returnValue = '';
            }
        }

        window.addEventListener('keydown', (event) => {
            if (event.key === 'F5' || ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'r')) {
                ignoreBeforeUnload = true;
            }
        });

        window.addEventListener('keyup', () => {
            ignoreBeforeUnload = false;
        });

        answerField.addEventListener('input', function () {
            updateWordCount();
            saveDraft();
        });

        window.addEventListener('beforeunload', confirmBeforeUnload);
        window.addEventListener('pageshow', function (event) {
            if (event.persisted && !isReload) {
                clearDraft();
            }
        });

        restoreDraft();
        updateWordCount();
        elapsedEl.textContent = format(elapsedSeconds);

        setInterval(() => {
            elapsedSeconds++;
            elapsedEl.textContent = format(elapsedSeconds);
            saveElapsedTime();
        }, 1000);

        function openModal() {
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        function clearDraft() {
            try {
                localStorage.removeItem(storageKey);
                localStorage.removeItem(timeKey);
            } catch (e) {
                // ignore storage errors
            }
        }

        function submitWithMethod(method) {
            gradingMethodField.value = method;
            updateAnswerInput();
            ignoreBeforeUnload = true;
            clearDraft();
            closeModal();
            form.submit();
        }

        submitBtn.addEventListener('click', function () {
            openModal();
        });

        closeModalBtn.addEventListener('click', function () {
            closeModal();
        });

        manualGradeBtn.addEventListener('click', function () {
            submitWithMethod('manual');
        });

        autoGradeBtn.addEventListener('click', function () {
            submitWithMethod('auto');
        });
    })();
</script>