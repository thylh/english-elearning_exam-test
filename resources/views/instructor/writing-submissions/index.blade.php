@extends('layouts.app')

@section('content')
    @include('partials.dashboard-header')

    <section class="hero">
        <div class="hero-content">
            <span class="hero-tag">WRITING SUBMISSIONS</span>
            <p>Danh sách bài viết cần chấm thủ công.</p>
        </div>
    </section>

    <div class="container my-4">
        @include('partials.alert')

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="{{ route('instructor.writing.submissions.index', ['status' => 'pending']) }}"
                    class="btn btn-outline-primary {{ $status === 'pending' ? 'active' : '' }}">Pending</a>
                <a href="{{ route('instructor.writing.submissions.index', ['status' => 'graded']) }}"
                    class="btn btn-outline-secondary {{ $status === 'graded' ? 'active' : '' }}">Graded</a>
            </div>
            <div class="text-muted">{{ $submissions->total() }} submission(s)</div>
        </div>

        @if($submissions->isEmpty())
            <div class="alert alert-info">Không có submission nào.</div>
        @else
            @foreach($submissions as $submission)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3 gap-3 flex-wrap">
                            <div>
                                <h5 class="card-title">{{ $submission->exam->title ?? 'Không rõ đề' }}</h5>
                                <p class="mb-1"><strong>Câu hỏi:</strong>
                                    {{ \Illuminate\Support\Str::limit($submission->examQuestion->question_text ?? '', 120) }}</p>
                                <p class="mb-1"><strong>Học viên:</strong> {{ optional($submission->user)->name ?? 'Khách' }}</p>
                                <p class="mb-1"><strong>Phương thức:</strong> {{ ucfirst($submission->grading_method) }}</p>
                                <p class="mb-1"><strong>Trạng thái:</strong> {{ ucfirst($submission->status) }}</p>
                                <p class="mb-0"><strong>Gửi lúc:</strong> {{ $submission->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                            @if($submission->status === 'graded')
                                <div class="text-end">
                                    <p class="mb-1"><strong>Điểm tự động:</strong> {{ $submission->auto_score ?? '—' }}</p>
                                    <p class="mb-1"><strong>Điểm thủ công:</strong> {{ $submission->manual_score ?? '—' }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <strong>Answer:</strong>
                            <pre class="p-3 bg-light" style="white-space: pre-wrap;">{{ $submission->answer_text }}</pre>
                        </div>

                        @if($submission->status === 'pending')
                            <form method="POST" action="{{ route('instructor.writing.submissions.update', $submission) }}">
                                @csrf
                                @method('PATCH')

                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Điểm thủ công</label>
                                        <input type="number" name="manual_score" class="form-control" min="0" max="100" step="0.1"
                                            value="{{ old('manual_score') }}" required>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label">Nhận xét</label>
                                        <textarea name="manual_feedback" class="form-control"
                                            rows="2">{{ old('manual_feedback') }}</textarea>
                                    </div>
                                </div>
                                <div class="mt-3 text-end">
                                    <button type="submit" class="btn btn-success">Lưu và đánh dấu đã chấm</button>
                                </div>
                            </form>
                        @else
                            <div class="mb-3">
                                <strong>Phản hồi tự động:</strong>
                                <div class="p-3 bg-light">{{ $submission->auto_feedback ?? 'Không có.' }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>Phản hồi thủ công:</strong>
                                <div class="p-3 bg-light">{{ $submission->manual_feedback ?? 'Không có.' }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            {{ $submissions->links() }}
        @endif
    </div>
@endsection