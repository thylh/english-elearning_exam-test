@extends('layouts.app')

@push('head')
    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')

    <!-- ================= HEADER ================= -->
    <header class="header">

        <!-- LOGO -->
        <div class="logo">

            <i class="fa-solid fa-graduation-cap"></i>

            <div class="logo-text">

                <h2>English For You</h2>

                <span>IELTS Learning Website</span>

            </div>

        </div>

        <!-- MENU -->
        <nav class="menu">

            <a href="/dashboard">

                <i class="fa-solid fa-house"></i>

                Trang chủ

            </a>

            <a href="/practice">

                <i class="fa-solid fa-book"></i>

                Practice

            </a>

            <a href="/reading">

                <i class="fa-solid fa-book-open"></i>

                Reading

            </a>

            <a href="/listening">

                <i class="fa-solid fa-headphones"></i>

                Listening

            </a>

            <a href="/writing">

                <i class="fa-solid fa-pen"></i>

                Writing

            </a>

            <a href="/speaking">

                <i class="fa-solid fa-microphone"></i>

                Speaking

            </a>

        </nav>

        <!-- USER -->
        <div class="user-menu">

            <!-- SEARCH -->
            <div class="search-box">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input type="text" placeholder="Tìm kiếm...">

            </div>

            <!-- PROFILE -->
            <div class="profile">

                <img src="https://i.pravatar.cc/100">

                <!-- DROPDOWN -->
                <div class="dropdown">

                    <a href="#">

                        <i class="fa-solid fa-user"></i>

                        Trang cá nhân

                    </a>

                    <a href="#">

                        <i class="fa-solid fa-chart-line"></i>

                        Kết quả học tập

                    </a>

                    <a href="#">

                        <i class="fa-solid fa-gear"></i>

                        Cài đặt

                    </a>

                    <a href="#">

                        <i class="fa-solid fa-right-from-bracket"></i>

                        Đăng xuất

                    </a>

                </div>

            </div>

        </div>

    </header>

    <!-- ================= HERO ================= -->
    <section class="hero">

        <!-- LEFT -->
        <div class="hero-content">

            <span class="hero-tag">

                IELTS LEARNING WEBSITE

            </span>

            <h1>

                Học Tiếng Anh
                <span>4 kỹ năng</span>
                đơn giản và hiệu quả

            </h1>

            <p>

                Website hỗ trợ luyện tập 4 kỹ năng
                Reading, Listening, Writing và Speaking.
                Giao diện đơn giản, dễ sử dụng
                dành cho sinh viên và người tự học.

            </p>

            <!-- BUTTON -->
            <div class="hero-buttons">

                <a href="/practice" class="btn-main">

                    Bắt đầu luyện tập

                </a>

                <a href="#about" class="btn-outline">

                    Giới thiệu

                </a>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="hero-image">

            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200">

        </div>

    </section>

    <!-- ================= ABOUT ================= -->
    <section class="about-section" id="about">

        <div class="about-box">

            <h2>

                About Us

            </h2>

            <p>

                English For You là website luyện tập IELTS
                được xây dựng nhằm hỗ trợ sinh viên
                và người học tiếng Anh tự luyện tập
                4 kỹ năng IELTS.

            </p>

            <p>

                Website bao gồm đầy đủ 4 kỹ năng:
                Reading, Listening, Writing và Speaking
                với giao diện mô phỏng hệ thống luyện thi thực tế.

            </p>


        </div>

    </section>



    <!-- ================= FOOTER ================= -->
    <footer class="footer">

        <p>
            Nền tảng tự học Tiéng Anh
            © 2026 English For You | IELTS Learning Website

        </p>

    </footer>

@endsection