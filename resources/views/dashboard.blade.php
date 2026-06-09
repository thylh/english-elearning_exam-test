@extends('layouts.app')

@push('head')
    @vite([
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')

    @include('partials.dashboard-header')

    <!-- ================= HERO ================= -->
    <section class="hero">

        <!-- LEFT -->
        <div class="hero-content">

            <span class="hero-tag" style="display: inline-block; transform: translateY(-100px);">
                English Learning Website
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

                English For You là website luyện tập tiếng Anh
                được xây dựng nhằm hỗ trợ sinh viên
                và người học tiếng Anh tự luyện tập
                4 kỹ năng.

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
            © 2026 English For You | English Learning Website

        </p>

    </footer>

@endsection