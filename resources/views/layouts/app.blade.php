<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        English Learning
    </title>

    <!-- GOOGLE FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- VITE -->
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="page-transition">

    {{-- AUTH PAGE --}}
    @if(
            Request::is('login') ||
            Request::is('register') ||
            Request::is('forgot-password') ||
            Request::is('reset-password/*')
        )

        @yield('content')

        {{-- NORMAL PAGE --}}
    @else

        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-3">

            <div class="container">

                <a class="navbar-brand fw-semibold" href="/">

                    English Learning

                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">

                    <span class="navbar-toggler-icon"></span>

                </button>

                <div class="collapse navbar-collapse" id="navbarContent">

                    <ul class="navbar-nav ms-auto align-items-lg-center">

                        @auth

                            <li class="nav-item me-3">

                                <span class="text-light">

                                    Xin chào,
                                    {{ auth()->user()->name }}

                                </span>

                            </li>

                            <li class="nav-item">

                                <form method="POST" action="{{ route('logout') }}">

                                    @csrf

                                    <button class="btn btn-outline-light btn-sm">

                                        Đăng xuất

                                    </button>

                                </form>

                            </li>

                        @else

                            <li class="nav-item me-2">

                                <a href="/login" class="btn btn-outline-light btn-sm">

                                    Đăng nhập

                                </a>

                            </li>

                            <li class="nav-item">

                                <a href="/register" class="btn btn-primary btn-sm">

                                    Đăng ký

                                </a>

                            </li>

                        @endauth

                    </ul>

                </div>

            </div>

        </nav>

        <!-- PAGE CONTENT -->
        <main class="py-4">

            <div class="container">

                @yield('content')

            </div>

        </main>

    @endif

</body>

</html>