<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Protected media route for serving private attachments (supports range requests)
Route::get('media/exam-question/{question}', [MediaController::class, 'serveExamQuestion'])
    ->name('media.exam_question');
Route::get('media/speaking-submission/{submission}', [MediaController::class, 'serveSpeakingSubmission'])
    ->name('media.speaking_submission');

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
    if (Auth::user()?->isAdmin()) {
        return redirect()->route('admin.overview');
    }

    if (Auth::user()?->isTeacher()) {
        return redirect()->route('teacher.exams.index');
    }

    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:student,teacher,instructor'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// =========================
// ENGLISH FOR YOU TEMPLATE
// =========================

Route::view('/english', 'ielts.index');
use App\Http\Controllers\PracticeController;
Route::get('/practice', [PracticeController::class, 'index']);
use App\Http\Controllers\ExamPublicController;
Route::get('/exam-test', [ExamPublicController::class, 'index'])->name('exam.index');
Route::get('/exams/{exam}/take', [ExamPublicController::class, 'take'])->name('exams.take');
Route::post('/exams/{exam}/submit', [ExamPublicController::class, 'submit'])->name('exams.submit');
Route::view('/reading', 'ielts.reading');
Route::view('/listening', 'ielts.listening');
Route::view('/writing', 'ielts.writing');
Route::view('/speaking', 'ielts.speaking');

// =========================
// TEACHER: Exam management
// =========================
use App\Http\Controllers\Teacher\ExamController;
use App\Http\Controllers\Teacher\ExamQuestionController;
use App\Http\Controllers\Teacher\SubmissionController;
use App\Http\Controllers\Teacher\StatsController;

Route::middleware(['auth', 'role:teacher,instructor'])->prefix('teacher')->name('teacher.')->group(function(){
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('exams.update');
    Route::patch('/exams/{exam}/classify', [ExamController::class, 'classify'])->name('exams.classify');
    Route::patch('/exams/{exam}/publish', [ExamController::class, 'togglePublish'])->name('exams.publish');
    Route::delete('/exams/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy');
    Route::get('/exams/{exam}/questions', [ExamQuestionController::class, 'index'])->name('exams.questions.index');
    Route::post('/exams/{exam}/questions', [ExamQuestionController::class, 'store'])->name('exams.questions.store');
    Route::put('/exams/{exam}/questions/{question}', [ExamQuestionController::class, 'update'])->name('exams.questions.update');
    Route::delete('/exams/{exam}/questions/{question}', [ExamQuestionController::class, 'destroy'])->name('exams.questions.destroy');
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::patch('/submissions/{submission}', [SubmissionController::class, 'update'])->name('submissions.update');
    Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');
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

// =========================
// LEARNING RESULTS
// =========================
use App\Http\Controllers\LearningResultController;
Route::middleware(['auth'])->group(function() {
    Route::get('/learning-results', [LearningResultController::class, 'index'])->name('learning-results.index');
    Route::get('/learning-results/{result}', [LearningResultController::class, 'show'])->name('learning-results.show');
});

// =========================
// ADMIN: User management
// =========================
use App\Http\Controllers\Admin\UserController as AdminUserController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminUserController::class, 'overview'])->name('overview');
    Route::get('/overview', [AdminUserController::class, 'overview'])->name('overview.page');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/students', [AdminUserController::class, 'students'])->name('students.index');
    Route::get('/teachers', [AdminUserController::class, 'teachers'])->name('teachers.index');
    Route::get('/backup', [AdminUserController::class, 'backup'])->name('backup.page');
    Route::get('/stats', [AdminUserController::class, 'stats'])->name('stats.index');

    Route::get('/backup/export', function () {
        $payload = [
            'exported_at' => now()->toIso8601String(),
            'users' => \App\Models\User::query()
                ->select(['id', 'name', 'email', 'role', 'created_at', 'updated_at'])
                ->orderBy('id')
                ->get()
                ->toArray(),
            'exams' => \App\Models\Exam::query()->orderBy('id')->get()->toArray(),
            'results' => \App\Models\Result::query()->orderBy('id')->get()->toArray(),
            'submissions' => \App\Models\Submission::query()->orderBy('id')->get()->toArray(),
        ];

        $filename = 'english-learning-backup-' . now()->format('Ymd_His') . '.json';

        return response()->streamDownload(function () use ($payload) {
            echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }, $filename, [
            'Content-Type' => 'application/json; charset=UTF-8',
        ]);
    })->name('backup.export');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});