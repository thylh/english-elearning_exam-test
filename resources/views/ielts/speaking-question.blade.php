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
                    @if($questions->isNotEmpty() && $questions[0])
                        @php $q = $questions[0]; @endphp

                        @if($q->question_text)
                            <p>{!! nl2br(e($q->question_text)) !!}</p>
                        @elseif($q->prompt_text)
                            <p>{!! nl2br(e($q->prompt_text)) !!}</p>
                        @endif

                        @if($q->prompt_attachment)
                            <div style="margin-top: 20px; padding: 15px; background: #f5f5f5; border-radius: 8px;">
                                <p style="margin: 0 0 10px 0; font-weight: 500;">Tệp đính kèm:</p>
                                <a href="{{ Storage::url($q->prompt_attachment) }}" target="_blank"
                                    style="color: #0066cc; text-decoration: none;">
                                    📎 {{ basename($q->prompt_attachment) }}
                                </a>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="reading-right">

                    <div class="reading-top">
                        <div class="reading-timer" id="timer"></div>
                        <div class="top-buttons">
                            <button type="button" id="submit-btn" class="submit-btn">Nộp bài</button>
                        </div>
                    </div>

                    <div class="reading-question" style="text-align: center; padding: 20px;">
                        <form id="exam-form">
                            <input type="hidden" name="answers[speaking]" id="speaking-answer" value="">
                            <input type="hidden" name="grading_method" id="grading-method" value="manual">
                        </form>
                        <div id="visualizer-container"
                            style="margin-bottom: 20px; padding: 15px; background: #f5f5f5; border-radius: 8px;">
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                                <div id="record-status" style="font-weight: 600; color: #333;">Chưa ghi âm</div>
                                <div style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: flex-end;">
                                    <button type="button" id="replay-btn"
                                        style="display: none; padding: 8px 14px; background: #fff; border: 1px solid #cbd5e1; border-radius: 8px; color: #1f2d6f; cursor: pointer;">Nghe
                                        lại</button>
                                    <button type="button" id="redo-btn"
                                        style="display: none; padding: 8px 14px; background: #fff; border: 1px solid #cbd5e1; border-radius: 8px; color: #1f2d6f; cursor: pointer;">Ghi
                                        lại</button>
                                </div>
                            </div>
                            <canvas id="waveform"
                                style="width: 100%; height: 100px; display: block; margin-top: 14px; border-radius: 6px;"></canvas>
                            <audio id="playback" controls
                                style="display: none; width: 100%; margin-top: 14px; border-radius: 8px;"></audio>
                        </div>
                        <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 12px;">
                            <button type="button" id="record-btn"
                                style="padding: 12px 24px; background: #8b7355; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 500; transition: all 0.3s ease;">
                                🎤 Record Answer
                            </button>
                        </div>
                    </div>

                </div>

            </section>

        </main>

    </div>

</body>

</html>

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

    #submit-btn {
        transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
    }

    #submit-btn:hover {
        transform: translateY(-2px);
        background: #12b76a;
        box-shadow: 0 14px 28px rgba(15, 157, 88, 0.22);
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
<script>
    (function () {
        const modal = document.getElementById('grading-modal');
        const closeModalBtn = document.getElementById('grading-modal-close');
        const manualGradeBtn = document.getElementById('manual-grade-btn');
        const autoGradeBtn = document.getElementById('auto-grade-btn');
        const gradingMethodField = document.getElementById('grading-method');

        const duration = {{ $exam->duration_minutes ?? 30 }} * 60;
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

        function format(ms) {
            const m = Math.floor(ms / 60).toString().padStart(2, '0');
            const s = (ms % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        function collectAnswers() {
            const fd = new FormData(form);
            const answers = {};
            for (const [k, v] of fd.entries()) {
                const m = k.match(/answers\[([^\]]+)\]/);
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
                localStorage.setItem(timeKey, String(remaining));
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
                remaining = value;
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

        function showGradingModal() {
            modal.classList.remove('hidden');
        }

        function hideGradingModal() {
            modal.classList.add('hidden');
        }

        async function submitWithGrading(gradingMethod) {
            hideGradingModal();
            gradingMethodField.value = gradingMethod;
            ignoreBeforeUnload = true;
            submitBtn.disabled = true;
            clearDraft();
            const answers = collectAnswers();
            const gradingMethodValue = gradingMethodField.value;
            const res = await fetch(`{{ route('exams.submit', $exam) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ answers, grading_method: gradingMethodValue })
            });
            if (res.redirected) window.location = res.url;
            else {
                const text = await res.text();
                document.body.innerHTML = text;
            }
        }

        async function submit() {
            showGradingModal();
        }

        form.addEventListener('input', saveAnswers);
        form.addEventListener('change', saveAnswers);
        restoreAnswers();
        restoreTime();

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

        timerEl.textContent = format(remaining);
        const iv = setInterval(() => {
            remaining--;
            timerEl.textContent = format(remaining);
            saveTime();
            if (remaining <= 0) {
                clearInterval(iv);
                submit();
            }
        }, 1000);

        const recordBtn = document.getElementById('record-btn');
        const speakingAnswer = document.getElementById('speaking-answer');
        const visualizerContainer = document.getElementById('visualizer-container');
        const recordStatus = document.getElementById('record-status');
        const replayBtn = document.getElementById('replay-btn');
        const redoBtn = document.getElementById('redo-btn');
        const playback = document.getElementById('playback');
        const canvas = document.getElementById('waveform');
        const canvasCtx = canvas.getContext('2d');
        let mediaRecorder;
        let audioChunks = [];
        let audioContext;
        let analyser;
        let animationId;
        let audioBlobUrl = null;

        function resetRecordingState() {
            recordStatus.textContent = 'Chưa ghi âm';
            replayBtn.style.display = 'none';
            redoBtn.style.display = 'none';
            playback.style.display = 'none';
            playback.src = '';
            if (audioBlobUrl) {
                URL.revokeObjectURL(audioBlobUrl);
                audioBlobUrl = null;
            }
            speakingAnswer.value = '';
            saveAnswers();
        }

        // Set canvas size and clear scale transform each time
        function resizeCanvas() {
            const ratio = window.devicePixelRatio || 1;
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvasCtx.setTransform(ratio, 0, 0, ratio, 0, 0);
            canvasCtx.fillStyle = 'rgb(245, 245, 245)';
            canvasCtx.fillRect(0, 0, canvas.offsetWidth, canvas.offsetHeight);
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        function drawWaveform() {
            if (!analyser) return;
            const bufferLength = analyser.frequencyBinCount;
            const dataArray = new Uint8Array(bufferLength);
            analyser.getByteFrequencyData(dataArray);

            const canvasWidth = canvas.offsetWidth;
            const canvasHeight = canvas.offsetHeight;

            canvasCtx.fillStyle = 'rgb(245, 245, 245)';
            canvasCtx.fillRect(0, 0, canvasWidth, canvasHeight);

            canvasCtx.lineWidth = 2;
            canvasCtx.strokeStyle = 'rgb(139, 115, 85)';
            canvasCtx.beginPath();

            const sliceWidth = canvasWidth / bufferLength;
            let x = 0;

            for (let i = 0; i < bufferLength; i++) {
                const v = dataArray[i] / 128.0;
                const y = (v * canvasHeight) / 2;

                if (i === 0) {
                    canvasCtx.moveTo(x, y);
                } else {
                    canvasCtx.lineTo(x, y);
                }

                x += sliceWidth;
            }

            canvasCtx.lineTo(canvasWidth, canvasHeight / 2);
            canvasCtx.stroke();

            if (mediaRecorder && mediaRecorder.state === 'recording') {
                animationId = requestAnimationFrame(drawWaveform);
            }
        }

        recordBtn.addEventListener('click', async () => {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                recordBtn.textContent = '🎤 Record Answer';
                recordBtn.classList.remove('recording');
                recordStatus.textContent = 'Đang xử lý, xin đợi...';
                if (animationId) cancelAnimationFrame(animationId);
            } else {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

                    if (!audioContext) {
                        audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    }
                    const source = audioContext.createMediaStreamSource(stream);
                    analyser = audioContext.createAnalyser();
                    analyser.fftSize = 2048;
                    source.connect(analyser);

                    mediaRecorder = new MediaRecorder(stream);
                    audioChunks = [];

                    mediaRecorder.ondataavailable = (event) => {
                        audioChunks.push(event.data);
                    };

                    mediaRecorder.onstop = () => {
                        const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                        if (audioBlobUrl) {
                            URL.revokeObjectURL(audioBlobUrl);
                        }
                        audioBlobUrl = URL.createObjectURL(audioBlob);
                        playback.src = audioBlobUrl;
                        playback.style.display = 'block';
                        replayBtn.style.display = 'inline-flex';
                        redoBtn.style.display = 'inline-flex';
                        recordStatus.textContent = 'Đã ghi âm xong. Nghe lại hoặc ghi lại.';

                        const reader = new FileReader();
                        reader.onloadend = () => {
                            speakingAnswer.value = reader.result;
                            saveAnswers();
                        };
                        reader.readAsDataURL(audioBlob);
                        stream.getTracks().forEach(track => track.stop());
                    };

                    resetRecordingState();
                    recordStatus.textContent = 'Đang ghi âm...';
                    replayBtn.style.display = 'none';
                    redoBtn.style.display = 'none';
                    playback.style.display = 'none';

                    mediaRecorder.start();
                    recordBtn.textContent = '⏹️ Stop Recording';
                    recordBtn.classList.add('recording');
                    drawWaveform();
                } catch (error) {
                    recordStatus.textContent = 'Không thể truy cập microphone.';
                    alert('Không thể truy cập microphone. Vui lòng kiểm tra quyền truy cập.');
                }
            }
        });

        replayBtn.addEventListener('click', () => {
            if (playback.src) {
                playback.play();
            }
        });

        redoBtn.addEventListener('click', () => {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
            }
            resetRecordingState();
            recordBtn.textContent = '🎤 Record Answer';
            recordBtn.classList.remove('recording');
        });

        closeModalBtn.addEventListener('click', hideGradingModal);
        manualGradeBtn.addEventListener('click', () => submitWithGrading('manual'));
        autoGradeBtn.addEventListener('click', () => submitWithGrading('auto'));

        modal.addEventListener('click', (e) => {
            if (e.target === modal) hideGradingModal();
        });

        submitBtn.addEventListener('click', submit);
    })();
</script>