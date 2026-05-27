@extends('layouts.app')

@section('content')

    <div class="register-page">

        <!-- BACKGROUND -->
        <div class="bg-circle bg-purple"></div>
        <div class="bg-circle bg-blue"></div>
        <div class="bg-circle bg-pink"></div>

        <div class="container">

            <div class="row align-items-center justify-content-center register-wrapper">

                <!-- LEFT -->
                <div class="col-lg-6 d-none d-lg-flex">

                    <div class="welcome-box w-100 text-center">

                        <img src="{{ asset('images/mascot.png') }}" class="mascot-img" alt="Mascot">

                        <div class="streak-box mx-auto mb-4">
                            🔥 Khám phá hành trình mới
                        </div>

                        <p class="welcome-text">
                            Mỗi ngày học là một bước tiến gần đến mục tiêu 🎯
                        </p>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="col-lg-6">

                    <div class="register-card mx-auto">

                        <!-- LOGO -->
                        <div class="logo-box mx-auto">
                            📘
                        </div>

                        <h1 class="register-title">
                            Tạo tài khoản
                        </h1>

                        <p class="register-subtitle">
                            Bắt đầu hành trình học tiếng Anh ngay hôm nay 🚀
                        </p>

                        <!-- FORM -->
                        <form method="POST" action="{{ route('register') }}">

                            @csrf

                            <!-- NAME -->
                            <div class="mb-3">

                                <label class="form-label custom-label">
                                    Họ và tên
                                </label>

                                <div class="input-wrapper">

                                    <span class="input-icon">
                                        👤
                                    </span>

                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control custom-input @error('name') is-invalid @enderror"
                                        placeholder="Nhập họ và tên" required>

                                </div>

                                @error('name')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <!-- EMAIL -->
                            <div class="mb-3">

                                <label class="form-label custom-label">
                                    Email
                                </label>

                                <div class="input-wrapper">

                                    <span class="input-icon">
                                        📧
                                    </span>

                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control custom-input @error('email') is-invalid @enderror"
                                        placeholder="Nhập email" required>

                                </div>

                                @error('email')
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
                                        placeholder="Nhập mật khẩu" required>

                                </div>

                                @error('password')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <!-- CONFIRM PASSWORD -->
                            <div class="mb-4">

                                <label class="form-label custom-label">
                                    Xác nhận mật khẩu
                                </label>

                                <div class="input-wrapper">

                                    <span class="input-icon">
                                        🔑
                                    </span>

                                    <input type="password" name="password_confirmation" class="form-control custom-input"
                                        placeholder="Nhập lại mật khẩu" required>

                                </div>

                                @error('password_confirmation')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>

                            <!-- BUTTON -->
                            <button type="submit" class="btn login-btn w-100">
                                Đăng ký →
                            </button>

                        </form>

                        {{-- <!-- DIVIDER -->
                        <div class="divider">
                            <span>hoặc</span>
                        </div> --}}

                        {{-- <!-- SOCIAL -->
                        <div class="row g-3">

                            <div class="col-6">

                                <button type="button" class="btn social-btn w-100">
                                    Google
                                </button>

                            </div>

                            <div class="col-6">

                                <button type="button" class="btn social-btn w-100">
                                    Facebook
                                </button>

                            </div>

                        </div> --}}

                        <!-- LOGIN -->
                        <div class="text-center mt-2">

                            <p class="mb-1 text-muted">
                                Đã có tài khoản?
                            </p>

                            <a href="{{ url('/login') }}" class="register-link">
                                Đăng nhập ngay
                            </a>

                        </div>

                        {{-- <!-- TERMS -->
                        <div class="terms text-center mt-4">

                            <p class="mb-1">
                                Bằng việc đăng ký, bạn đồng ý với
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