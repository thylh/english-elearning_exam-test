@extends('layouts.app')

@section('content')

    <div class="login-page">

        <div class="container">

            <div class="row justify-content-center align-items-center login-wrapper">

                <div class="col-lg-5">

                    <div class="login-card mx-auto">

                        <div class="logo-box mx-auto">
                            🔑
                        </div>

                        <h1 class="login-title">
                            Quên mật khẩu
                        </h1>

                        <p class="login-subtitle">
                            Nhập email để đặt lại mật khẩu
                        </p>

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('forgot.password.check') }}">

                            @csrf

                            <div class="mb-4">

                                <label class="custom-label">
                                    Email
                                </label>

                                <input type="email" name="email" class="form-control custom-input" placeholder="Nhập email">

                            </div>

                            <button class="btn login-btn w-100">
                                Tiếp tục
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection