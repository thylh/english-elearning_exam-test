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
// ENGLISH FOR YOU TEMPLATE
// =========================

Route::view('/english', 'ielts.index');
Route::view('/practice', 'ielts.practice');
Route::view('/reading', 'ielts.reading');
Route::view('/listening', 'ielts.listening');
Route::view('/writing', 'ielts.writing');
Route::view('/speaking', 'ielts.speaking');

// =========================
// INSTRUCTOR: Exam management
// =========================
use App\Http\Controllers\Instructor\ExamController;

Route::middleware(['auth'])->prefix('instructor')->name('instructor.')->group(function(){
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{exam}/edit', [ExamController::class, 'edit'])->name('exams.edit');
    Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('exams.update');
    Route::delete('/exams/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy');
});

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