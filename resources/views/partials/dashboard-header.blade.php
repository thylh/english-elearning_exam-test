<!-- ================= HEADER ================= -->
<header class="header">

    <!-- LOGO -->
    <div class="logo">

        <i class="fa-solid fa-graduation-cap"></i>

        <div class="logo-text">

            <h2>English For You</h2>

            <span>English Learning Website</span>

        </div>

    </div>

    <!-- MENU -->
    <nav class="menu">
        @auth
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.overview') }}" class="{{ Request::is('admin*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i>
                    Bảng điều khiển
                </a>
                <a href="{{ route('admin.backup.page') }}">
                    <i class="fa-solid fa-download"></i>
                    Sao lưu dữ liệu
                </a>
            @elseif(Auth::user()->isTeacher())
                <a href="/teacher/exams">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Quản lý đề
                </a>
                <a href="{{ route('teacher.submissions.index') }}">
                    <i class="fa-solid fa-clipboard-check"></i>
                    Chấm thi
                </a>
                <a href="{{ route('teacher.stats.index') }}" class="{{ Request::is('teacher/stats*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-bar"></i>
                    Thống kê
                </a>
            @else
                <a href="/dashboard" class="{{ Request::is('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i>
                    Trang chủ
                </a>
                <a href="/practice" class="{{ Request::is('practice') || Request::is('exams/*/take') ? 'active' : '' }}">
                    <i class="fa-solid fa-book"></i>
                    Practice
                </a>
                <a href="/exam-test" class="{{ Request::is('exam-test') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Exam test
                </a>
                <a href="{{ route('learning-results.index') }}" class="{{ Request::is('learning-results*') ? 'active' : '' }}">
                    <i class="fa-solid fa-square-poll-vertical"></i>
                    Kết quả học tập
                </a>
            @endif
        @endauth
    </nav>

    <!-- USER -->
    <div class="user-menu">

        <!-- GREETING -->
        <div class="greeting">
            @auth
                @if(Auth::user()->isAdmin())
                    <span>Xin chào, admin <strong>{{ Auth::user()->name }}</strong></span>
                @elseif(Auth::user()->isTeacher())
                    <span>Xin chào, giáo viên <strong>{{ Auth::user()->name }}</strong></span>
                @else
                    <span>Xin chào, học viên <strong>{{ Auth::user()->name }}</strong></span>
                @endif
            @endauth
        </div>

        <!-- PROFILE -->
        <div class="profile">

            <img src="https://i.pravatar.cc/100">

            <!-- DROPDOWN -->
            <div class="dropdown">
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.overview') }}">
                        <i class="fa-solid fa-chart-pie"></i>
                        Bảng điều khiển
                    </a>
                    <a href="{{ route('admin.backup.page') }}">
                        <i class="fa-solid fa-download"></i>
                        Sao lưu dữ liệu
                    </a>
                @elseif(Auth::user()->isTeacher())
                    <a href="/practice">
                        <i class="fa-solid fa-book"></i>
                        Practice
                    </a>
                    <a href="/exam-test">
                        <i class="fa-solid fa-clipboard-list"></i>
                        Exam test
                    </a>
                @else
                    <a href="/dashboard">
                        <i class="fa-solid fa-house"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('learning-results.index') }}">
                        <i class="fa-solid fa-square-poll-vertical"></i>
                        Kết quả học tập
                    </a>
                @endif
                @unless(Auth::user()->isAdmin())
                    <a href="{{ route('profile.edit') }}">
                        <i class="fa-solid fa-user-gear"></i>
                        Hồ sơ cá nhân
                    </a>
                @endunless

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                    <i class="fa-solid fa-right-from-bracket"></i>
                    Đăng xuất
                </a>

            </div>

        </div>

    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

</header>