@extends('layouts.app')

@section('content')

    <div class="login-page">

        <div class="container">

            <div class="row justify-content-center align-items-center login-wrapper">

                <div class="col-lg-5">

                    <div class="login-card mx-auto">

                        <div class="logo-box mx-auto">
                            🔒
                        </div>

                        <h1 class="login-title">
                            Đặt lại mật khẩu
                        </h1>

                        <p class="login-subtitle">
                            Nhập mật khẩu mới
                        </p>

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('reset.password.update') }}">

                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">

                                <label class="custom-label">
                                    Mật khẩu mới
                                </label>

                                <input type="password" name="password" class="form-control custom-input">
                            </div>

                            <div class="mb-4">

                                <label class="custom-label">
                                    Xác nhận mật khẩu
                                </label>

                                <input type="password" name="password_confirmation" class="form-control custom-input">
                            </div>

                            <button class="btn login-btn w-100">
                                Đổi mật khẩu
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection