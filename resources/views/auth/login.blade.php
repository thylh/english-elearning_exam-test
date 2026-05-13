@extends('layouts.app')

@section('content')

    <div class="login-page">

        <!-- BACKGROUND -->
        <div class="bg-circle bg-purple"></div>
        <div class="bg-circle bg-blue"></div>
        <div class="bg-circle bg-pink"></div>

        <div class="container">

            <div class="row align-items-center justify-content-center login-wrapper">

                <!-- LEFT -->
                <div class="col-lg-6 d-none d-lg-flex">

                    <div class="welcome-box w-100 text-center">

                        <img src="{{ asset('images/mascot.png') }}" class="mascot-img" alt="Mascot">

                        <div class="streak-box mx-auto mb-4">
                            🔥 Bước tiến tới ước mơ
                        </div>

                        <p class="welcome-text">
                            Mỗi ngày học là một bước tiến gần đến mục tiêu 🎯
                        </p>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="col-lg-6">

                    <div class="login-card mx-auto">

                        <!-- LOGO -->
                        <div class="logo-box mx-auto">
                            📘
                        </div>

                        <h1 class="login-title">
                            Chào mừng trở lại!
                        </h1>

                        <p class="login-subtitle">
                            Tiếp tục hành trình học tập của bạn 🚀
                        </p>

                        <!-- ERROR -->
                        @if(session('error'))
                            <div class="alert alert-danger auto-hide-alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- FORM -->
                        <form method="POST" action="{{ route('login') }}">

                            @csrf

                            <!-- EMAIL -->
                            <div class="mb-3">

                                <label class="form-label custom-label">
                                    Email hoặc tên đăng nhập
                                </label>

                                <div class="input-wrapper">

                                    <span class="input-icon">
                                        👤
                                    </span>

                                    <input type="text" name="login" value="{{ old('login') }}"
                                        class="form-control custom-input @error('login') is-invalid @enderror"
                                        placeholder="Nhập email hoặc tên đăng nhập">

                                </div>

                                @error('login')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <!-- PASSWORD -->
                            <div class="mb-3">

                                <label class="form-label custom-label">
                                    Mật khẩu
                                </label>

                                <div class="input-wrapper">

                                    <span class="input-icon">
                                        🔒
                                    </span>

                                    <input type="password" name="password"
                                        class="form-control custom-input @error('password') is-invalid @enderror"
                                        placeholder="Nhập mật khẩu">

                                </div>

                                @error('password')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <!-- OPTIONS -->
                            <div class="d-flex justify-content-between align-items-center mb-4">

                                <div class="form-check">

                                    <input class="form-check-input" type="checkbox" name="remember">

                                    <label class="form-check-label">
                                        Ghi nhớ đăng nhập
                                    </label>

                                </div>

                                <a href="{{ route('forgot.password') }}" class="forgot-link">
                                    Quên mật khẩu?
                                </a>

                            </div>

                            <!-- BUTTON -->
                            <button type="submit" class="btn login-btn w-100">
                                Đăng nhập →
                            </button>

                        </form>

                        <!-- DIVIDER -->
                        <div class="divider">
                            <span>hoặc</span>
                        </div>

                        <!-- SOCIAL -->
                        <div class="row g-3">

                            <div class="col-6">

                                <button type="button" class="btn social-btn w-100">
                                    <img src="{{ asset('images/google-icon.svg') }}" alt="Google">
                                    Google
                                </button>

                            </div>

                            <div class="col-6">

                                <button type="button" class="btn social-btn w-100">
                                    <img src="{{ asset('images/facebook-icon.svg') }}" alt="Facebook">
                                    Facebook
                                </button>

                            </div>

                        </div>

                        <!-- REGISTER -->
                        <div class="text-center mt-4">

                            <p class="mb-1 text-muted">
                                Chưa có tài khoản?
                            </p>

                            <a href="{{ url('/register') }}" class="register-link">
                                Đăng ký ngay
                            </a>

                        </div>

                        {{-- <!-- TERMS -->
                        <div class="terms text-center mt-4">

                            <p class="mb-1">
                                Bằng việc đăng nhập, bạn đồng ý với
                            </p>

                            <a href="#">
                                Điều khoản
                            </a>

                            <div>và</div>

                            <a href="#">
                                Chính sách bảo mật
                            </a>

                        </div> --}}

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection