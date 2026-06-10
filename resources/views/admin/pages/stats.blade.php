@extends('admin.layout')

{{-- @section('topbar-right')
<a href="{{ route('admin.backup.page') }}" class="btn"><i class="fa-solid fa-download"></i>Sao lưu</a>
@endsection --}}

@section('content')
    @php
        $maxVisits = max(1, max($activityData ?? [0]));
        $avgScore = $overviewStats['avg_score'] !== null ? number_format($overviewStats['avg_score'], 2) : '0.00';
    @endphp

    <div class="grid grid-4" style="margin-bottom:16px;">
        <div class="card">
            <div class="label">Truy cập hôm nay</div>
            <div class="value">{{ number_format($overviewStats['visits_today']) }}</div>
            <div class="mini">Admin visits</div>
        </div>
        <div class="card">
            <div class="label">Truy cập 7 ngày</div>
            <div class="value">{{ number_format($overviewStats['visits_7d']) }}</div>
            <div class="mini">Tổng lượt mở dashboard</div>
        </div>
        <div class="card">
            <div class="label">Đề xuất bản</div>
            <div class="value">{{ number_format($overviewStats['published_exams']) }}</div>
            <div class="mini">Sẵn sàng cho học viên</div>
        </div>
        <div class="card">
            <div class="label">Điểm trung bình</div>
            <div class="value">{{ $avgScore }}</div>
            <div class="mini">Trên các bài đã chấm</div>
        </div>
    </div>

    <div class="grid grid-2" style="margin-bottom:16px;">
        <div class="card">
            <div class="row" style="justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
                <div>
                    <div class="label">Biểu đồ truy cập</div>
                    <h3 style="margin:8px 0 0;">7 ngày gần nhất</h3>
                </div>
                <div class="mini">Số lượt</div>
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
            <div class="label">Phân tích dữ liệu</div>
            <div class="quick" style="margin-top:14px;">
                <div class="item">
                    <div class="k">Tổng người dùng</div>
                    <div class="v">{{ number_format($overviewStats['total_users']) }}</div>
                </div>
                <div class="item">
                    <div class="k">Tổng đề thi</div>
                    <div class="v">{{ number_format($overviewStats['total_exams']) }}</div>
                </div>
                <div class="item">
                    <div class="k">Tổng kết quả</div>
                    <div class="v">{{ number_format($overviewStats['total_results']) }}</div>
                </div>
                <div class="item">
                    <div class="k">Bài chấm thủ công</div>
                    <div class="v">{{ number_format($overviewStats['manual_submissions']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <div class="label">Tỉ lệ người dùng</div>
            <div class="bars" style="margin-top:14px;">
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

        <div class="card">
            <div class="label">Tổng hợp hệ thống</div>
            <table class="table" style="margin-top:14px;">
                <tbody>
                    <tr>
                        <td style="padding-left:0;">Người dùng mới 30 ngày</td>
                        <td style="text-align:right;">{{ number_format($overviewStats['new_users_30d']) }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left:0;">Đề đã xuất bản</td>
                        <td style="text-align:right;">{{ number_format($overviewStats['published_exams']) }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left:0;">Bài chờ chấm</td>
                        <td style="text-align:right;">{{ number_format($overviewStats['pending_manual_submissions']) }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left:0;">Truy cập hôm nay</td>
                        <td style="text-align:right;">{{ number_format($overviewStats['visits_today']) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection