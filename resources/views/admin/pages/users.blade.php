@extends('admin.layout')

@section('topbar-right')
    <form method="GET" action="{{ url()->current() }}" class="search" style="margin-left:auto;">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" name="search" value="{{ $search }}" placeholder="Tìm tên, email...">
    </form>
@endsection

@section('content')
    @php
        $roleLabel = $roleFilter === 'student' ? 'Học viên' : 'Giảng viên';
        $targetRole = $roleFilter === 'student' ? 'student' : 'teacher';
        $alternateRole = $roleFilter === 'student' ? 'teacher' : 'student';
    @endphp

    <div class="hero" style="margin-bottom:16px;">
        <div class="row" style="justify-content:space-between;flex-wrap:wrap;">
            <div>
                <div class="label">Danh sách {{ strtolower($roleLabel) }}</div>
                <h2 style="margin:8px 0 6px;font-size:28px;">{{ $roleLabel }}</h2>
                <div class="muted">Danh sách bên dưới chỉ hiển thị {{ strtolower($roleLabel) }} để dễ phân quyền và xử lý
                    nhanh.</div>
            </div>
            {{-- <div class="row" style="flex-wrap:wrap;">
                <a href="{{ route('admin.overview') }}" class="btn"><i class="fa-solid fa-chart-pie"></i>Tổng quan</a>
                <a href="{{ route('admin.backup.page') }}" class="btn"><i class="fa-solid fa-download"></i>Sao lưu</a>
            </div> --}}
        </div>
    </div>

    <div class="grid grid-4" style="margin-bottom:16px;">
        {{-- <div class="card">
            <div class="label">Tổng hệ thống</div>
            <div class="value">{{ number_format($roleCounts['all']) }}</div>
            <div class="mini">Toàn bộ tài khoản</div>
        </div> --}}
        <div class="card">
            <div class="label">Học viên</div>
            <div class="value">{{ number_format($roleCounts['student']) }}</div>
            <div class="mini">Đã đăng ký tài khoản</div>
        </div>
        <div class="card">
            <div class="label">Giảng viên</div>
            <div class="value">{{ number_format($roleCounts['teacher']) }}</div>
            <div class="mini">Được phép quản lý đề/chấm</div>
        </div>
        {{-- <div class="card">
            <div class="label">Admin</div>
            <div class="value">{{ number_format($roleCounts['admin']) }}</div>
            <div class="mini">Hệ thống</div>
        </div> --}}
    </div>

    <div class="panel" style="overflow:hidden;">
        <div class="row"
            style="justify-content:space-between;padding:22px 22px 16px;border-bottom:1px solid var(--border);flex-wrap:wrap;gap:12px;">
            <div>
                <div class="label">Danh sách {{ strtolower($roleLabel) }}</div>
                <h3 style="margin:8px 0 0;">{{ $users->total() }} tài khoản</h3>
            </div>
            {{-- <div class="row" style="flex-wrap:wrap;">
                <a href="{{ route('admin.students.index') }}" class="btn"
                    style="{{ $targetRole === 'student' ? 'border-color:var(--accent);color:var(--accent-lt);' : '' }}">Học
                    viên</a>
                <a href="{{ route('admin.teachers.index') }}" class="btn"
                    style="{{ $targetRole === 'teacher' ? 'border-color:var(--accent);color:var(--accent-lt);' : '' }}">Giảng
                    viên</a>
            </div> --}}
        </div>

        <div style="padding:0 22px 20px;">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Người dùng</th>
                        <th>Vai trò</th>
                        <th>Ngày tham gia</th>
                        <th>Phân quyền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td>
                                <div class="row">
                                    <div class="avatar" style="width:44px;height:44px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:700;font-size:16px;">{{ $user->name }}</div>
                                        <div class="mini">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="role-badge {{ $user->role === 'admin' ? 'badge-admin' : ($user->role === 'teacher' ? 'badge-teacher' : 'badge-student') }}">
                                    <i class="fa-solid fa-user-tag"></i>{{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $user->created_at->format('d/m/Y') }}</div>
                                <div class="mini">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                @if($user->id === auth()->id())
                                    <div class="mini">Không đổi chính mình</div>
                                @else
                                    <form method="POST" action="{{ route('admin.users.update', $user) }}"
                                        style="display:flex;gap:8px;align-items:center;">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="btn" style="padding:9px 12px;min-width:150px;">
                                            <option value="student" @selected($user->role === 'student')>Học viên</option>
                                            <option value="teacher" @selected($user->role === 'teacher')>Giảng viên</option>
                                            <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                        </select>
                                        <button class="btn" type="submit">Lưu</button>
                                    </form>
                                @endif
                            </td>
                            <td>
                                @if($user->id === auth()->id())
                                    <span class="mini">Không thể xoá</span>
                                @else
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                        onsubmit="return confirm('Xóa người dùng {{ $user->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn" type="submit" style="border-color:rgba(239,68,68,.35);color:#f87171;">
                                            <i class="fa-solid fa-trash"></i>Xóa
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="muted">Không có dữ liệu phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="row" style="justify-content:space-between;padding:0 22px 22px;flex-wrap:wrap;gap:12px;">
            <div class="mini">Hiển thị {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} /
                {{ $users->total() }} {{ strtolower($roleLabel) }}
            </div>
            <div>{{ $users->links() }}</div>
        </div>
    </div>
@endsection