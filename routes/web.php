<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::get('/', function () {
    return view('welcome');
});

// =========================
// AUTHENTICATION
// =========================

Route::get('/register', [RegisterController::class, 'showRegister']);
Route::post('/register', [RegisterController::class, 'register'])
    ->name('register');
Route::get('/login', [LoginController::class, 'showLogin']);
Route::post('/login', [LoginController::class, 'login'])
    ->name('login');
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

// =========================
// DASHBOARD
// =========================

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// =========================
// PASSWORD RESET
// =========================

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot.password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'checkEmail'])
    ->name('forgot.password.check');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('reset.password');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('reset.password.update');