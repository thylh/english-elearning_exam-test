@extends('admin.layout')

@section('content')
    <div class="hero">
        <div class="label">Trang cũ đã được tách</div>
        <h2 style="margin:8px 0 6px;font-size:28px;">Bảng điều khiển admin</h2>
        <div class="muted" style="max-width:720px;">
            Các chức năng đã được chia thành các trang riêng ở thanh bên trái:
            Tổng quan, Học viên, Giảng viên, Sao lưu và Thống kê dữ liệu hệ thống.
        </div>
        <div class="row" style="margin-top:18px;flex-wrap:wrap;">
            <a href="{{ route('admin.overview') }}" class="btn"><i class="fa-solid fa-chart-pie"></i>Tổng quan</a>
            <a href="{{ route('admin.students.index') }}" class="btn"><i class="fa-solid fa-user-graduate"></i>Học viên</a>
            <a href="{{ route('admin.teachers.index') }}" class="btn"><i class="fa-solid fa-chalkboard-teacher"></i>Giảng viên</a>
            <a href="{{ route('admin.backup.page') }}" class="btn"><i class="fa-solid fa-download"></i>Sao lưu</a>
            <a href="{{ route('admin.stats.index') }}" class="btn"><i class="fa-solid fa-chart-line"></i>Thống kê</a>
        </div>
    </div>
@endsection
