@extends('admin.layout')

@section('topbar-right')
    {{-- <a href="{{ route('admin.backup.page') }}" class="btn"><i class="fa-solid fa-download"></i>Sao lưu</a> --}}
@endsection

@section('content')
    @php
        $maxVisits = max(1, max($activityData ?? [0]));
        $avgScore = $overviewStats['avg_score'] !== null ? number_format($overviewStats['avg_score'], 2) : '0.00';
    @endphp

    <div class="hero" style="margin-bottom:16px;">
        <div class="row" style="justify-content:space-between;flex-wrap:wrap;">
            <div>
                <div class="label">Bảng điều khiển</div>
                <h2 style="margin:8px 0 6px;font-size:28px;">Tổng quan hệ thống</h2>
                <div class="muted">Theo dõi truy cập, người dùng, đề thi, kết quả và hoạt động chấm điểm.</div>
            </div>
            <div class="row" style="flex-wrap:wrap;">
                <a href="{{ route('admin.stats.index') }}" class="btn"><i class="fa-solid fa-chart-line"></i>Thống kê</a>
                <a href="{{ route('admin.backup.export') }}" class="btn"><i class="fa-solid fa-file-arrow-down"></i>Tải JSON
                    backup</a>
            </div>
        </div>
    </div>

    <div class="grid grid-4" style="margin-bottom:16px;">
        <div class="card">
            <div class="label">Truy cập hôm nay</div>
            <div class="value">{{ number_format($overviewStats['visits_today']) }}</div>
            <div class="mini">Lượt vào khu quản trị trong ngày</div>
        </div>
        <div class="card">
            <div class="label">Truy cập 7 ngày</div>
            <div class="value">{{ number_format($overviewStats['visits_7d']) }}</div>
            <div class="mini">Xu hướng sử dụng gần nhất</div>
        </div>
        <div class="card">
            <div class="label">Tổng người dùng</div>
            <div class="value">{{ number_format($overviewStats['total_users']) }}</div>
            <div class="mini">Học viên, giảng viên và admin</div>
        </div>
        <div class="card">
            <div class="label">Người dùng mới 30 ngày</div>
            <div class="value">{{ number_format($overviewStats['new_users_30d']) }}</div>
            <div class="mini">Tài khoản tạo mới gần đây</div>
        </div>
    </div>

    <div class="grid grid-4" style="margin-bottom:16px;">
        <div class="card">
            <div class="label">Tổng đề thi</div>
            <div class="value">{{ number_format($overviewStats['total_exams']) }}</div>
            <div class="mini">Toàn bộ đề đã tạo</div>
        </div>
        <div class="card">
            <div class="label">Đề đã xuất bản</div>
            <div class="value">{{ number_format($overviewStats['published_exams']) }}</div>
            <div class="mini">Đề có thể làm bài</div>
        </div>
        <div class="card">
            <div class="label">Tổng kết quả</div>
            <div class="value">{{ number_format($overviewStats['total_results']) }}</div>
            <div class="mini">Bài đã nộp và được lưu</div>
        </div>
        <div class="card">
            <div class="label">Điểm trung bình</div>
            <div class="value">{{ $avgScore }}</div>
            <div class="mini">Chỉ tính bài đã chấm</div>
        </div>
    </div>

    <div class="grid grid-2" style="margin-bottom:16px;">
        <div class="card">
            <div class="row" style="justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
                <div>
                    <div class="label">Hoạt động truy cập</div>
                    <h3 style="margin:8px 0 0;">7 ngày gần nhất</h3>
                </div>
                <div class="mini">{{ number_format($maxVisits) }} lượt/ngày cao nhất</div>
            </div>
            <div class="bars">
                @foreach($activityLabels as $index => $label)
                    @php
                        $value = $activityData[$index] ?? 0;
                        $width = $maxVisits > 0 ? max(6, round(($value / $maxVisits) * 100)) : 6;
                    @endphp
                    <div class="bar-row">
                        <div class="mini">{{ $label }}</div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: {{ $width }}%"></div>
                        </div>
                        <div class="mini" style="text-align:right;">{{ $value }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="row" style="justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
                <div>
                    <div class="label">Phân bổ người dùng</div>
                    <h3 style="margin:8px 0 0;">Theo vai trò</h3>
                </div>
                <div class="mini">Tỷ lệ trên tổng số người dùng</div>
            </div>
            <div class="bars">
                <div class="bar-row">
                    <div class="mini">Học viên</div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width: {{ $rolePercentages['student'] }}%"></div>
                    </div>
                    <div class="mini" style="text-align:right;">{{ $rolePercentages['student'] }}%</div>
                </div>
                <div class="bar-row">
                    <div class="mini">Giảng viên</div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width: {{ $rolePercentages['teacher'] }}%"></div>
                    </div>
                    <div class="mini" style="text-align:right;">{{ $rolePercentages['teacher'] }}%</div>
                </div>
                <div class="bar-row">
                    <div class="mini">Admin</div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width: {{ $rolePercentages['admin'] }}%"></div>
                    </div>
                    <div class="mini" style="text-align:right;">{{ $rolePercentages['admin'] }}%</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <div class="label">Chấm thủ công</div>
            <div class="quick" style="margin-top:14px;">
                <div class="item">
                    <div class="k">Bài cần chấm</div>
                    <div class="v">{{ number_format($overviewStats['manual_submissions']) }}</div>
                </div>
                <div class="item">
                    <div class="k">Đang chờ xử lý</div>
                    <div class="v">{{ number_format($overviewStats['pending_manual_submissions']) }}</div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="label">Điều hướng nhanh</div>
            <div class="quick" style="margin-top:14px;">
                <a class="item" href="{{ route('admin.students.index') }}" style="text-decoration:none;color:inherit;">
                    <div class="k">Học viên</div>
                    <div class="v">{{ number_format($roleCounts['student']) }}</div>
                </a>
                <a class="item" href="{{ route('admin.teachers.index') }}" style="text-decoration:none;color:inherit;">
                    <div class="k">Giảng viên</div>
                    <div class="v">{{ number_format($roleCounts['teacher']) }}</div>
                </a>
                <a class="item" href="{{ route('admin.backup.page') }}" style="text-decoration:none;color:inherit;">
                    <div class="k">Sao lưu dữ liệu</div>
                    <div class="v">JSON</div>
                </a>
                <a class="item" href="{{ route('admin.stats.index') }}" style="text-decoration:none;color:inherit;">
                    <div class="k">Thống kê hệ thống</div>
                    <div class="v">Báo cáo</div>
                </a>
            </div>
        </div>
    </div>
@endsection