<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminDashboardVisit;
use App\Models\Exam;
use App\Models\Result;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function overview(Request $request)
    {
        return $this->renderPage($request, 'overview');
    }

    public function students(Request $request)
    {
        return $this->renderPage($request, 'students', 'student');
    }

    public function teachers(Request $request)
    {
        return $this->renderPage($request, 'teachers', 'teacher');
    }

    public function backup(Request $request)
    {
        return $this->renderPage($request, 'backup');
    }

    public function stats(Request $request)
    {
        return $this->renderPage($request, 'stats');
    }

    public function index()
    {
        return redirect()->route('admin.overview');
    }

    private function renderPage(Request $request, string $page, ?string $roleFilter = null)
    {
        $search = trim($request->input('search', ''));

        AdminDashboardVisit::create([
            'user_id' => Auth::id(),
            'path' => $request->path(),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);

        $query = User::query()->orderBy('created_at', 'desc');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleFilter && in_array($roleFilter, ['student', 'teacher', 'admin'], true)) {
            $query->where('role', $roleFilter);
        }

        $users = $query->paginate(20)->appends($request->query());

        $roleCounts = [
            'all' => User::count(),
            'student' => User::where('role', 'student')->count(),
            'teacher' => User::where('role', 'teacher')->count(),
            'admin' => User::where('role', 'admin')->count(),
        ];

        $now = Carbon::now();
        $visitsToday = AdminDashboardVisit::query()
            ->whereDate('created_at', $now->toDateString())
            ->count();
        $visits7Days = AdminDashboardVisit::query()
            ->where('created_at', '>=', $now->copy()->subDays(6)->startOfDay())
            ->count();
        $dailyVisits = AdminDashboardVisit::query()
            ->where('created_at', '>=', $now->copy()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as visit_date, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('visit_date')
            ->get()
            ->keyBy('visit_date');

        $activityLabels = [];
        $activityData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i);
            $key = $day->format('Y-m-d');
            $activityLabels[] = $day->format('d/m');
            $activityData[] = isset($dailyVisits[$key]) ? (int) $dailyVisits[$key]->total : 0;
        }

        $overviewStats = [
            'visits_today' => $visitsToday,
            'visits_7d' => $visits7Days,
            'total_users' => $roleCounts['all'],
            'new_users_30d' => User::where('created_at', '>=', $now->copy()->subDays(29)->startOfDay())->count(),
            'total_exams' => Exam::count(),
            'published_exams' => Exam::where('published', true)->count(),
            'total_results' => Result::count(),
            'avg_score' => Result::where('status', 'graded')->whereNotNull('score')->avg('score'),
            'manual_submissions' => Submission::where('grading_method', 'manual')->count(),
            'pending_manual_submissions' => Submission::where('grading_method', 'manual')->where('status', 'pending')->count(),
        ];

        $rolePercentages = collect([
            'student' => $roleCounts['student'],
            'teacher' => $roleCounts['teacher'],
            'admin' => $roleCounts['admin'],
        ])->map(function ($count) use ($roleCounts) {
            return $roleCounts['all'] > 0 ? round(($count / $roleCounts['all']) * 100, 1) : 0;
        });

        $heading = 'Bảng điều khiển';
        $subheading = 'Tổng quan hệ thống, truy cập và dữ liệu chính';

        switch ($page) {
            case 'students':
                $heading = 'Quản trị học viên';
                $subheading = 'Lọc, phân quyền và theo dõi học viên';
                break;
            case 'teachers':
                $heading = 'Quản trị giảng viên';
                $subheading = 'Lọc, phân quyền và theo dõi giảng viên';
                break;
            case 'backup':
                $heading = 'Sao lưu dữ liệu';
                $subheading = 'Xuất bản sao dữ liệu hệ thống ra JSON';
                break;
            case 'stats':
                $heading = 'Thống kê dữ liệu hệ thống';
                $subheading = 'Các chỉ số tổng hợp phục vụ vận hành';
                break;
        }

        $view = match ($page) {
            'overview' => 'admin.pages.overview',
            'students', 'teachers' => 'admin.pages.users',
            'backup' => 'admin.pages.backup',
            'stats' => 'admin.pages.stats',
            default => 'admin.pages.overview',
        };

        return view($view, compact(
            'page',
            'users',
            'search',
            'roleFilter',
            'roleCounts',
            'overviewStats',
            'activityLabels',
            'activityData',
            'rolePercentages',
            'heading',
            'subheading'
        ));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['student', 'teacher', 'admin'])],
        ]);

        if ($user->id === Auth::id() && $data['role'] !== 'admin') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không thể thay đổi role của chính mình.',
                ], 403);
            }

            return back()->with('error', 'Bạn không thể thay đổi role của chính mình.');
        }

        $user->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật role của {$user->name} thành {$data['role']}.",
                'role' => $data['role'],
            ]);
        }

        return back()->with('success', "Đã cập nhật role của {$user->name}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể xóa tài khoản của chính mình.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Đã xóa người dùng \"{$name}\" thành công.");
    }
}
