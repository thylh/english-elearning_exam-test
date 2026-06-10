@extends('admin.layout')

@section('topbar-right')
    <a href="{{ route('admin.backup.export') }}" class="btn"><i class="fa-solid fa-file-arrow-down"></i>Tải JSON backup</a>
@endsection

@section('content')
    <div class="hero" style="margin-bottom:16px;">
        <div class="row" style="justify-content:space-between;flex-wrap:wrap;">
            <div>
                <div class="label">Sao lưu dữ liệu</div>
                <h2 style="margin:8px 0 6px;font-size:28px;">Xuất bản sao hệ thống</h2>
                <div class="muted">Bản sao hiện tại gồm người dùng, đề thi, kết quả và bài nộp.</div>
            </div>
            <a href="{{ route('admin.backup.export') }}" class="btn"><i class="fa-solid fa-download"></i>Xuất ngay</a>
        </div>
    </div>

    <div class="grid grid-4" style="margin-bottom:16px;">
        <div class="card">
            <div class="label">Người dùng</div>
            <div class="value">{{ number_format($roleCounts['all']) }}</div>
            <div class="mini">Sẽ được lưu trong JSON</div>
        </div>
        <div class="card">
            <div class="label">Đề thi</div>
            <div class="value">{{ number_format($overviewStats['total_exams']) }}</div>
            <div class="mini">Bao gồm trạng thái xuất bản</div>
        </div>
        <div class="card">
            <div class="label">Kết quả</div>
            <div class="value">{{ number_format($overviewStats['total_results']) }}</div>
            <div class="mini">Dữ liệu làm bài và chấm điểm</div>
        </div>
        <div class="card">
            <div class="label">Bài nộp</div>
            <div class="value">
                {{ number_format($overviewStats['manual_submissions'] + $overviewStats['pending_manual_submissions']) }}
            </div>
            <div class="mini">Gồm bài tự động và thủ công</div>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <div class="label">Phạm vi backup</div>
            <div style="margin-top:14px;" class="bars">
                <div class="bar-row">
                    <div class="mini">users</div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width:100%"></div>
                    </div>
                    <div class="mini" style="text-align:right;">Có</div>
                </div>
                <div class="bar-row">
                    <div class="mini">exams</div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width:100%"></div>
                    </div>
                    <div class="mini" style="text-align:right;">Có</div>
                </div>
                <div class="bar-row">
                    <div class="mini">results</div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width:100%"></div>
                    </div>
                    <div class="mini" style="text-align:right;">Có</div>
                </div>
                <div class="bar-row">
                    <div class="mini">submissions</div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width:100%"></div>
                    </div>
                    <div class="mini" style="text-align:right;">Có</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="label">Hành động</div>
            <div class="quick" style="margin-top:14px;">
                <a href="{{ route('admin.backup.export') }}" class="item" style="text-decoration:none;color:inherit;">
                    <div class="k">Tải bản sao</div>
                    <div class="v">JSON</div>
                </a>
                <a href="{{ route('admin.stats.index') }}" class="item" style="text-decoration:none;color:inherit;">
                    <div class="k">Xem thống kê</div>
                    <div class="v">Báo cáo</div>
                </a>
            </div>
        </div>
    </div>
@endsection