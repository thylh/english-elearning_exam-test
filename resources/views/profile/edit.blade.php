<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hồ sơ cá nhân - English For You</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite([
        'resources/css/app.css',
        'resources/css/english-for-you.css',
        'resources/js/english-for-you.js'
    ])

    <style>
        :root {
            --profile-surface: rgba(255, 255, 255, 0.92);
            --profile-border: rgba(147, 99, 75, 0.14);
            --profile-shadow: 0 20px 46px rgba(80, 47, 28, 0.08);
            --profile-text: #3f2a23;
            --profile-muted: #8b6b5b;
        }

        body {
            background:
                radial-gradient(circle at top left, rgba(251, 191, 36, 0.15), transparent 26%),
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.11), transparent 22%),
                linear-gradient(180deg, #fffaf6 0%, #fff7f2 100%);
            color: var(--profile-text);
            font-family: 'Be Vietnam Pro', system-ui, sans-serif;
        }

        .profile-shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 24px 56px;
        }

        .profile-hero {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 20px;
        }

        .profile-hero h1 {
            margin: 0 0 8px;
            font-size: clamp(2rem, 3vw, 2.8rem);
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .profile-hero p {
            margin: 0;
            color: var(--profile-muted);
        }

        .profile-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            background: var(--profile-surface);
            border: 1px solid var(--profile-border);
            box-shadow: var(--profile-shadow);
            font-weight: 700;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
            gap: 18px;
        }

        @media (max-width: 980px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        .profile-card {
            background: var(--profile-surface);
            border: 1px solid var(--profile-border);
            border-radius: 24px;
            box-shadow: var(--profile-shadow);
            padding: 24px;
            backdrop-filter: blur(12px);
        }

        .profile-summary {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .avatar-block {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(147, 99, 75, 0.12);
        }

        .avatar-large {
            width: 76px;
            height: 76px;
            border-radius: 24px;
            display: grid;
            place-items: center;
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            flex: none;
        }

        .summary-name {
            margin: 0 0 4px;
            font-size: 1.2rem;
            font-weight: 800;
        }

        .summary-role {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            border-radius: 999px;
            background: rgba(249, 115, 22, 0.12);
            color: #c2410c;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .summary-list {
            display: grid;
            gap: 12px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(147, 99, 75, 0.1);
        }

        .summary-label {
            color: var(--profile-muted);
            font-size: 0.88rem;
        }

        .summary-value {
            font-weight: 700;
            text-align: right;
        }

        .section-title {
            margin: 0 0 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            font-weight: 800;
        }

        .section-title i {
            color: #b45309;
        }

        .section-note {
            margin: -6px 0 18px;
            color: var(--profile-muted);
            font-size: 0.92rem;
        }

        .flash-success {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 16px;
            background: rgba(16, 185, 129, 0.12);
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.18);
            font-weight: 600;
        }

        .form-grid {
            display: grid;
            gap: 14px;
        }

        .field-group {
            display: grid;
            gap: 8px;
        }

        .field-group label {
            font-weight: 700;
            font-size: 0.92rem;
        }

        .field-group input {
            width: 100%;
            border: 1px solid rgba(147, 99, 75, 0.16);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.9);
            padding: 14px 16px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .field-group input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.12);
        }

        .field-error {
            color: #b91c1c;
            font-size: 0.84rem;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 6px;
        }

        .btn-primary {
            border: 0;
            border-radius: 14px;
            padding: 12px 18px;
            background: linear-gradient(135deg, #f59e0b, #ea580c);
            color: #fff;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 14px 30px rgba(234, 88, 12, 0.22);
        }

        .btn-primary:hover {
            filter: brightness(1.02);
        }

        .divider {
            height: 1px;
            background: rgba(147, 99, 75, 0.12);
            margin: 18px 0;
        }
    </style>
</head>
<body class="page-transition">
    @include('partials.dashboard-header')

    <main class="profile-shell">
        <section class="profile-hero">
            <div>
                <h1>Hồ sơ cá nhân</h1>
                <p>Quản lý thông tin tài khoản và đổi mật khẩu cho cả học viên lẫn giảng viên.</p>
            </div>

            <div class="profile-badge">
                <i class="fa-solid fa-user"></i>
                {{ ucfirst(Auth::user()->role) }}
            </div>
        </section>

        @if(session('success'))
            <div class="flash-success">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="profile-grid">
            <aside class="profile-card profile-summary">
                <div class="avatar-block">
                    <div class="avatar-large">
                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="summary-name">{{ $user->name }}</h2>
                        <div class="summary-role">
                            <i class="fa-solid fa-id-badge"></i>
                            {{ $user->role === 'instructor' ? 'Giảng viên' : 'Học viên' }}
                        </div>
                    </div>
                </div>

                <div class="summary-list">
                    <div class="summary-item">
                        <div class="summary-label">Email</div>
                        <div class="summary-value">{{ $user->email }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Ngày tạo tài khoản</div>
                        <div class="summary-value">{{ optional($user->created_at)->format('d/m/Y') ?? '—' }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Quyền truy cập</div>
                        <div class="summary-value">{{ ucfirst($user->role) }}</div>
                    </div>
                </div>
            </aside>

            <section class="profile-card" id="profile-info">
                <h2 class="section-title">
                    <i class="fa-solid fa-user-pen"></i>
                    Chỉnh sửa thông tin cá nhân
                </h2>
                <p class="section-note">Cập nhật tên hiển thị và địa chỉ email đăng nhập.</p>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="form-grid">
                        <div class="field-group">
                            <label for="name">Họ và tên</label>
                            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Lưu thay đổi
                        </button>
                    </div>
                </form>

                <div class="divider"></div>

                <div id="profile-password">
                    <h2 class="section-title">
                        <i class="fa-solid fa-key"></i>
                        Đổi mật khẩu
                    </h2>
                    <p class="section-note">Mật khẩu mới phải có ít nhất 6 ký tự.</p>

                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-grid">
                            <div class="field-group">
                                <label for="current_password">Mật khẩu hiện tại</label>
                                <input id="current_password" type="password" name="current_password" required>
                                @error('current_password')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field-group">
                                <label for="password">Mật khẩu mới</label>
                                <input id="password" type="password" name="password" required>
                                @error('password')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field-group">
                                <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="actions">
                            <button type="submit" class="btn-primary">
                                <i class="fa-solid fa-shield-halved"></i>
                                Đổi mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
