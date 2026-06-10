@extends('layouts.app')

@push('head')
    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])
@endpush

@php
    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;

    $filterTypes = ['practice' => 'Practice', 'exam' => 'Exam'];
    $filterSubtypes = ['single' => 'Đề lẻ', 'full' => 'Full bài'];
    $filterSkills = [
        'reading' => 'Reading',
        'listening' => 'Listening',
        'writing' => 'Writing',
        'speaking' => 'Speaking',
    ];

    $selectedTypes = Arr::wrap($types ?? []);
    $selectedSubtypes = Arr::wrap($subtypes ?? []);
    $selectedSkills = Arr::wrap($skills ?? []);
    $selectedBand = $band ?? '';
    $filterOpen = count($selectedTypes) || count($selectedSubtypes) || count($selectedSkills) || trim($selectedBand) !== '';
@endphp

@section('content')
    @include('partials.dashboard-header')

    <section class="hero">
        <div class="hero-content">
            <span class="hero-tag">TEACHER DASHBOARD</span>
            {{-- <h1> Quản lý đề thi</h1> --}}
        </div>
    </section>

    <div class="container mt-4">
        @include('partials.alert')

        <div class="practice-layout exam-list-layout">
            <style>
                .exam-list-layout {
                    display: block;
                    background: #f5f0ea;
                    padding: 20px;
                }

                .exam-topbar {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 16px;
                    /* margin-bottom: 0px; */
                    flex-wrap: wrap;
                }

                .exam-topbar .filter-icon {
                    font-size: 24px;
                    color: #6d4c41;
                    background: #fff;
                    padding: 14px;
                    border-radius: 18px;
                    border: 1px solid #ddd;
                    cursor: pointer;
                }

                .exam-topbar h2 {
                    margin: 0;
                    font-size: 28px;
                    color: #3e2723;
                }

                .exam-topbar p {
                    margin: 4px 0 0;
                    color: #5d4037;
                }

                .practice-content {
                    padding: 0;
                }

                .exam-filter-panel {
                    background: #fff;
                    border: 1px solid #dfdbd3;
                    border-radius: 16px;
                    padding: 18px;
                    margin-top: 18px;
                    display: none;
                    gap: 18px;
                }

                .exam-filter-panel.open {
                    display: grid;
                }

                .filter-icon {
                    cursor: pointer;
                }

                .filter-row {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                    gap: 16px;
                }

                .filter-group {
                    display: flex;
                    flex-direction: column;
                    gap: 0.5rem;
                }

                .filter-checkbox {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    margin-right: 10px;
                    margin-bottom: 6px;
                    font-size: 14px;
                    color: #4b4036;
                }

                .filter-band-group .form-control {
                    min-width: 120px;
                }

                .filter-actions {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                    justify-content: flex-end;
                }

                .exam-card-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(480px, 1fr));
                    gap: 30px;
                }

                .practice-card {
                    background: #fff;
                    border-radius: 25px;
                    overflow: hidden;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                    position: relative;
                    display: flex;
                    gap: 20px;
                }

                .practice-card img {
                    width: 180px;
                    height: 140px;
                    object-fit: cover;
                    display: block;
                    border-radius: 15px;
                    margin-top: 35px;
                }

                .card-content {
                    flex: 1;
                    margin-top: 35px;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                }

                .card-tag {
                    position: absolute;
                    top: 0;
                    left: 0;
                    background: #3e2723;
                    color: white;
                    padding: 10px 20px;
                    border-radius: 25px 0 20px 0;
                    font-size: 16px;
                }

                .band-tag {
                    display: inline-block;
                    background: #ff7043;
                    color: white;
                    padding: 8px 16px;
                    border-radius: 20px;
                    margin-bottom: 15px;
                    font-size: 14px;
                }

                .card-actions {
                    margin-top: 18px;
                    display: flex;
                    justify-content: flex-end;
                    gap: 8px;
                    flex-wrap: wrap;
                }

                .card-actions button {
                    min-width: 90px;
                }

                .btn-icon {
                    width: 38px;
                    height: 38px;
                    border-radius: 12px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                }

                .card-icons {
                    position: absolute;
                    top: 16px;
                    right: 16px;
                    display: flex;
                    gap: 8px;
                    z-index: 10;
                }

                @media(max-width:1100px) {
                    .exam-card-grid {
                        grid-template-columns: repeat(2, minmax(280px, 1fr));
                    }
                }

                @media(max-width:760px) {
                    .exam-card-grid {
                        grid-template-columns: 1fr;
                    }

                    .card-icons {
                        position: static;
                        margin: 16px 0 0;
                    }

                    .practice-card {
                        flex-direction: column;
                    }

                    .practice-card img {
                        width: 100%;
                        height: 180px;
                        margin-top: 0;
                        border-radius: 0;
                    }
                }
            </style>

            <div class="exam-topbar">
                <div class="filter-icon">
                    <i class="fa-solid fa-filter"></i>
                </div>
                <div>
                    <h2>Danh sách đề</h2>
                </div>
                <button id="openCreateModal" type="button" class="btn btn-main">Tạo đề mới</button>
            </div>

            <form method="GET" action="{{ route('teacher.exams.index') }}"
                class="exam-filter-panel {{ $filterOpen ? 'open' : '' }}" style="margin-bottom: 20px;">
                <div class="filter-row">
                    <div class="filter-group">
                        <strong>Loại đề</strong>
                        @foreach($filterTypes as $value => $label)
                            <label class="filter-checkbox">
                                <input type="checkbox" name="type[]" value="{{ $value }}" @checked(in_array($value, $selectedTypes))>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    <div class="filter-group">
                        <strong>Dạng đề</strong>
                        @foreach($filterSubtypes as $value => $label)
                            <label class="filter-checkbox">
                                <input type="checkbox" name="subtype[]" value="{{ $value }}" @checked(in_array($value, $selectedSubtypes))>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    <div class="filter-group">
                        <strong>Kỹ năng</strong>
                        @foreach($filterSkills as $value => $label)
                            <label class="filter-checkbox">
                                <input type="checkbox" name="skill[]" value="{{ $value }}" @checked(in_array($value, $selectedSkills))>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    <div class="filter-group filter-band-group">
                        <label class="form-label mb-2"><strong>Band</strong></label>
                        <input type="text" name="band" value="{{ $selectedBand }}" class="form-control"
                            placeholder="Ví dụ: 7">
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Áp dụng lọc</button>
                    <a href="{{ route('teacher.exams.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                </div>
            </form>

            @if($exams->count())
                <main class="practice-content">
                    <div class="practice-grid exam-card-grid">
                        @foreach($exams as $exam)
                            <div class="practice-card {{ $exam->published ? 'highlight-card' : '' }}">
                                <div class="card-icons">
                                    <button type="button" class="btn btn-icon btn-secondary edit-btn" data-id="{{ $exam->id }}"
                                        data-title="{{ e($exam->title) }}" data-description="{{ e($exam->description) }}"
                                        style="font-size:14px;padding:8px;" title="Sửa"><i
                                            class="fa-solid fa-pen-to-square"></i></button>
                                    <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-danger"
                                            onclick="return confirm('Xóa đề này?')" style="font-size:14px;padding:8px;"
                                            title="Xóa"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>

                                <div class="card-tag">{{ $exam->category ?? 'Nah' }}</div>
                                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200"
                                    alt="Exam thumbnail">
                                <div class="card-content">
                                    <div>
                                        <div class="band-tag">{{ $exam->band ? 'Band ' . $exam->band : 'Band Nah' }}</div>
                                        <h3>{{ $exam->title }}</h3>
                                        <p>{{ Str::limit($exam->description ?? 'Không có mô tả', 120) }}</p>
                                    </div>
                                    <div class="card-actions">
                                        <a href="{{ route('teacher.exams.questions.index', $exam) }}"
                                            class="btn btn-outline-secondary" style="font-size:12px;padding:6px 12px;">Quản lý câu
                                            hỏi</a>
                                        <button type="button" class="btn btn-outline-primary classify-btn" data-id="{{ $exam->id }}"
                                            data-type="{{ $exam->type }}" data-band="{{ $exam->band }}"
                                            data-category="{{ $exam->category }}" data-subtype="{{ $exam->subtype }}"
                                            data-skill="{{ $exam->skill }}" style="font-size:12px;padding:6px 12px;">Phân
                                            loại</button>
                                        <button type="button"
                                            class="btn {{ $exam->published ? 'btn-danger' : 'btn-success' }} publish-btn"
                                            data-id="{{ $exam->id }}" data-published="{{ $exam->published ? 1 : 0 }}"
                                            style="font-size:12px;padding:6px 12px;">{{ $exam->published ? 'Unpublish' : 'Publish' }}</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{ $exams->links() }}
                </main>
            @else
                <div class="alert alert-info">Chưa có đề nào.</div>
            @endif

        </div>

        <!-- MODAL TẠO / SỬA ĐỀ -->
        <div id="createEditModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="createEditModalLabel" class="modal-title">Tạo đề mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="createEditForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="editMethod" value="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Tiêu đề đề</label>
                                <input type="text" name="title" id="editTitle" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" id="editDescription" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL PHÂN LOẠI ĐỀ -->
        <div id="classifyModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Phân loại đề</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="classifyForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <input type="hidden" id="examId" name="exam_id">

                            <div class="mb-3">
                                <label class="form-label">Loại đề</label>
                                <select id="typeSelect" name="type" class="form-control" required>
                                    <option value="">Chọn loại đề</option>
                                    <option value="practice">Practice</option>
                                    <option value="exam">Exam</option>
                                </select>
                            </div>

                            <div class="mb-3" id="subtypeDiv" style="display:none;">
                                <label class="form-label">Dạng đề (Practice)</label>
                                <select name="subtype" class="form-control">
                                    <option value="">Không chọn</option>
                                    <option value="single">Đề lẻ</option>
                                    <option value="full">Full bài</option>
                                </select>
                            </div>

                            <div class="mb-3" id="skillDiv" style="display:none;">
                                <label class="form-label">Kỹ năng (Practice)</label>
                                <select id="skillSelect" name="skill" class="form-control">
                                    <option value="">Chọn kỹ năng</option>
                                    <option value="reading">Reading</option>
                                    <option value="listening">Listening</option>
                                    <option value="writing">Writing</option>
                                    <option value="speaking">Speaking</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Band</label>
                                <input type="text" name="band" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Chủ đề</label>
                                <input type="text" name="category" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                const classifyModal = new bootstrap.Modal(document.getElementById('classifyModal'), {});
                const createEditModal = new bootstrap.Modal(document.getElementById('createEditModal'), {});
                const typeSelect = document.getElementById('typeSelect');
                const subtypeDiv = document.getElementById('subtypeDiv');
                const skillDiv = document.getElementById('skillDiv');
                const skillSelect = document.getElementById('skillSelect');
                const createEditForm = document.getElementById('createEditForm');
                const createEditModalLabel = document.getElementById('createEditModalLabel');
                const editTitle = document.getElementById('editTitle');
                const editDescription = document.getElementById('editDescription');
                const editMethod = document.getElementById('editMethod');

                function openCreateModal() {
                    createEditModalLabel.textContent = 'Tạo đề mới';
                    editMethod.value = 'POST';
                    createEditForm.action = '/teacher/exams';
                    editTitle.value = '';
                    editDescription.value = '';
                    createEditModal.show();
                }

                function openEditModal(id, title, description) {
                    createEditModalLabel.textContent = 'Sửa đề';
                    editMethod.value = 'PUT';
                    createEditForm.action = `/teacher/exams/${id}`;
                    editTitle.value = title;
                    editDescription.value = description;
                    createEditModal.show();
                }

                document.getElementById('openCreateModal').addEventListener('click', openCreateModal);
                document.querySelector('.filter-icon')?.addEventListener('click', function () {
                    const filterPanel = document.querySelector('.exam-filter-panel');
                    if (filterPanel) {
                        filterPanel.classList.toggle('open');
                    }
                });

                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        openEditModal(this.dataset.id, this.dataset.title, this.dataset.description);
                    });
                });

                createEditForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const method = editMethod.value;
                    if (method === 'PUT') {
                        formData.append('_method', 'PUT');
                    }

                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    })
                        .then(res => {
                            if (!res.ok) {
                                return res.json().then(data => { throw new Error(data?.message || 'Lỗi máy chủ'); });
                            }
                            return res.json();
                        })
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Lỗi: ' + (data.message || 'Không thể lưu đề.'));
                            }
                        })
                        .catch(err => alert('Lỗi: ' + err.message));
                });

                // Toggle subtype when type changes
                typeSelect.addEventListener('change', function () {
                    if (this.value === 'practice') {
                        subtypeDiv.style.display = 'block';
                        skillDiv.style.display = 'block';
                    } else {
                        subtypeDiv.style.display = 'none';
                        skillDiv.style.display = 'none';
                        document.querySelector('select[name="subtype"]').value = '';
                        skillSelect.value = '';
                    }
                });

                // Handle classify button click
                document.querySelectorAll('.classify-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const examId = this.dataset.id;
                        const type = this.dataset.type;
                        const band = this.dataset.band;
                        const category = this.dataset.category;
                        const subtype = this.dataset.subtype;
                        const skill = this.dataset.skill;

                        document.getElementById('examId').value = examId;
                        typeSelect.value = type || '';
                        document.querySelector('input[name="band"]').value = band || '';
                        document.querySelector('input[name="category"]').value = category || '';
                        document.querySelector('select[name="subtype"]').value = subtype || '';
                        skillSelect.value = skill || '';

                        // Trigger change to show/hide subtype
                        typeSelect.dispatchEvent(new Event('change'));

                        classifyModal.show();
                    });
                });

                // Handle classify form submit
                document.getElementById('classifyForm').addEventListener('submit', function (e) {
                    e.preventDefault();
                    if (!typeSelect.value) {
                        alert('Vui lòng chọn loại đề.');
                        typeSelect.focus();
                        return;
                    }
                    if (typeSelect.value === 'practice' && !skillSelect.value) {
                        alert('Vui lòng chọn kỹ năng.');
                        skillSelect.focus();
                        return;
                    }

                    const examId = document.getElementById('examId').value;
                    const formData = new FormData(this);

                    fetch(`/teacher/exams/${examId}/classify`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                        .then(res => {
                            if (!res.ok) {
                                return res.json().then(data => { throw new Error(data?.message || 'Lỗi máy chủ'); });
                            }
                            return res.json();
                        })
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Lỗi: ' + (data.message || 'Không thể cập nhật'));
                            }
                        })
                        .catch(err => alert('Lỗi: ' + err.message));
                });

                // Handle publish button click
                document.querySelectorAll('.publish-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const examId = this.dataset.id;
                        const isPublished = parseInt(this.dataset.published);
                        const action = isPublished ? 'ẩn' : 'hiện';

                        if (confirm(`Có chắc muốn ${action} đề này không?`)) {
                            const formData = new FormData();
                            formData.append('_method', 'PATCH');
                            formData.append('published', isPublished ? 0 : 1);

                            fetch(`/teacher/exams/${examId}/publish`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                },
                                body: formData
                            })
                                .then(res => {
                                    if (!res.ok) {
                                        return res.json().then(data => { throw new Error(data?.message || 'Lỗi máy chủ'); });
                                    }
                                    return res.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        location.reload();
                                    } else {
                                        alert('Lỗi: ' + (data.message || 'Không thể cập nhật'));
                                    }
                                })
                                .catch(err => alert('Lỗi: ' + err.message));
                        }
                    });
                });
            </script>
        @endpush


    </div>
@endsection