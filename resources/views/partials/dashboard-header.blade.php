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
            @if(Auth::user()->role === 'instructor')
                <a href="/instructor/exams">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Quản lý đề
                </a>
                <a href="{{ route('instructor.submissions.index') }}">
                    <i class="fa-solid fa-clipboard-check"></i>
                    Chấm thi
                </a>
                <a href="{{ route('instructor.stats.index') }}" class="{{ Request::is('instructor/stats*') ? 'active' : '' }}">
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
                @if(Auth::user()->role === 'instructor')
                    <span>Xin chào, giảng viên <strong>{{ Auth::user()->name }}</strong></span>
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
                @if(Auth::user()->role === 'instructor')
                    <a href="/dashboard">
                        <i class="fa-solid fa-house"></i>
                        Dashboard
                    </a>
                @else
                    <a href="/dashboard">
                        <i class="fa-solid fa-house"></i>
                        Dashboard
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}">
                    <i class="fa-solid fa-user-gear"></i>
                    Hồ sơ cá nhân
                </a>

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