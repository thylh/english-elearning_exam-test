<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root{--sidebar-w:260px;--bg:#0f1117;--bg-panel:#16181f;--bg-card:#1e2130;--bg-hover:#252840;--border:#2a2d3e;--accent:#6c63ff;--accent-lt:#8b85ff;--text:#e2e8f0;--text-muted:#8892a4;--green:#22c55e;--blue:#3b82f6;--red:#ef4444;--amber:#f59e0b;--shadow-sm:0 2px 8px rgba(0,0,0,.35);--shadow-md:0 4px 24px rgba(0,0,0,.45);--r-md:12px;--r-lg:16px;--r-xl:20px}
        *{box-sizing:border-box}html,body{margin:0}body{font-family:Inter,sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;overflow-x:hidden}
        .sidebar{width:var(--sidebar-w);background:var(--bg-panel);border-right:1px solid var(--border);position:fixed;inset:0 auto 0 0;display:flex;flex-direction:column;z-index:20}
        .sidebar-logo{padding:24px 20px;border-bottom:1px solid var(--border);display:flex;gap:12px;align-items:center}
        .logo-icon{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--accent),#a855f7);display:flex;align-items:center;justify-content:center}
        .logo-text strong{display:block;font-size:15px}.logo-text span{font-size:11px;color:var(--text-muted)}
        .sidebar-nav{flex:1;padding:16px 12px;overflow:auto}.nav-section{margin-bottom:22px}.nav-label{font-size:10px;letter-spacing:1.2px;text-transform:uppercase;color:#4e5568;font-weight:700;padding:0 8px 6px}
        .nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:12px;color:var(--text-muted);text-decoration:none;font-size:13.5px;font-weight:600;margin-bottom:4px}
        .nav-item:hover,.nav-item.active{background:var(--bg-hover);color:#fff}.nav-item i{width:18px;text-align:center}.nav-item .badge{margin-left:auto;background:rgba(108,99,255,.12);color:var(--accent-lt);padding:2px 8px;border-radius:999px;font-size:11px}
        .sidebar-footer{padding:16px 12px;border-top:1px solid var(--border)}.user-info{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:12px}.user-info:hover{background:var(--bg-hover)}.avatar{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--accent),#a855f7);font-weight:800}.user-details{flex:1;min-width:0}.user-details .name{font-size:13px;font-weight:700}.user-details .role{font-size:11px;color:var(--text-muted)}.logout-btn{color:var(--text-muted)}
        .main{margin-left:var(--sidebar-w);flex:1;min-height:100vh;display:flex;flex-direction:column}.topbar{height:64px;background:var(--bg-panel);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 24px;position:sticky;top:0;z-index:10}.topbar h1{font-size:17px;margin:0}.topbar p{margin:2px 0 0;color:var(--text-muted);font-size:12px}
        .page-content{padding:24px;flex:1}.panel{background:var(--bg-card);border:1px solid var(--border);border-radius:24px;box-shadow:var(--shadow-sm)}.hero{padding:24px 26px;border-radius:28px;background:linear-gradient(135deg,rgba(108,99,255,.16),rgba(59,130,246,.1));border:1px solid rgba(108,99,255,.22)}
        .grid{display:grid;gap:16px}.grid-4{grid-template-columns:repeat(4,minmax(0,1fr))}.grid-2{grid-template-columns:repeat(2,minmax(0,1fr))}.grid-1{grid-template-columns:1fr}
        .card{background:var(--bg-card);border:1px solid var(--border);border-radius:20px;padding:18px;box-shadow:var(--shadow-sm)}.card .label{font-size:12px;color:var(--text-muted);text-transform:uppercase;font-weight:700;letter-spacing:.8px}.card .value{font-size:32px;font-weight:800;margin:12px 0 8px}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:14px;background:var(--bg-card);border:1px solid var(--border);color:var(--text);text-decoration:none;font-weight:700;font-size:13px}.btn:hover{border-color:var(--accent);color:var(--accent-lt)}
        .table{width:100%;border-collapse:collapse}.table th,.table td{padding:14px 18px;border-bottom:1px solid var(--border);text-align:left}.table th{font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:#64748b}.role-badge{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:700}.badge-admin{background:rgba(239,68,68,.15);color:#f87171}.badge-teacher{background:rgba(34,197,94,.15);color:#4ade80}.badge-student{background:rgba(59,130,246,.15);color:#60a5fa}.search{position:relative}.search i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#4e5568}.search input{background:var(--bg-card);border:1px solid var(--border);border-radius:16px;padding:9px 14px 9px 36px;color:var(--text);width:240px}.muted{color:var(--text-muted)}.row{display:flex;align-items:center;gap:12px}.mini{font-size:12px;color:var(--text-muted)}.bars{display:flex;flex-direction:column;gap:12px}.bar-row{display:grid;grid-template-columns:84px 1fr 40px;gap:10px;align-items:center}.bar-track{height:10px;border-radius:999px;background:rgba(255,255,255,.04);overflow:hidden;border:1px solid rgba(255,255,255,.04)}.bar-fill{height:100%;border-radius:inherit;background:linear-gradient(90deg,var(--accent),var(--blue))}.quick{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.quick .item{background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:18px;padding:14px}.quick .k{font-size:12px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.7px}.quick .v{font-size:24px;font-weight:800;margin-top:8px}
        @media (max-width:1024px){.grid-4,.grid-2{grid-template-columns:1fr 1fr}.grid-4,.grid-2,.quick{grid-template-columns:1fr}}
        @media (max-width:768px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.page-content{padding:16px}.topbar{padding:0 16px}.search{display:none}}
    </style>
    @stack('head')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <div class="logo-text">
                <strong>English For You</strong>
                <span>Admin Control Panel</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-label">Bảng điều khiển</div>
                <a href="{{ route('admin.overview') }}" class="nav-item {{ Request::routeIs('admin.overview', 'admin.overview.page') ? 'active' : '' }}"><i class="fa-solid fa-chart-pie"></i>Tổng quan</a>
            </div>
            <div class="nav-section">
                <div class="nav-label">Người dùng</div>
                <a href="{{ route('admin.students.index') }}" class="nav-item {{ Request::routeIs('admin.students.*') ? 'active' : '' }}"><i class="fa-solid fa-user-graduate"></i>Học viên <span class="badge">{{ $roleCounts['student'] ?? '' }}</span></a>
                <a href="{{ route('admin.teachers.index') }}" class="nav-item {{ Request::routeIs('admin.teachers.*') ? 'active' : '' }}"><i class="fa-solid fa-chalkboard-teacher"></i>Giảng viên <span class="badge">{{ $roleCounts['teacher'] ?? '' }}</span></a>
            </div>
            <div class="nav-section">
                <div class="nav-label">Sao lưu</div>
                <a href="{{ route('admin.backup.page') }}" class="nav-item {{ Request::routeIs('admin.backup.*') ? 'active' : '' }}"><i class="fa-solid fa-download"></i>Sao lưu</a>
            </div>
            <div class="nav-section">
                <div class="nav-label">Thống kê hệ thống</div>
                <a href="{{ route('admin.stats.index') }}" class="nav-item {{ Request::routeIs('admin.stats.*') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i>Dữ liệu hệ thống</a>
            </div>
        </nav>
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="user-details">
                    <div class="name">{{ Auth::user()->name }}</div>
                    <div class="role">Administrator</div>
                </div>
                <a href="{{ route('logout') }}" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        </div>
    </aside>
    <div class="main">
        <header class="topbar">
            <div class="row">
                <button class="btn" onclick="toggleSidebar()" style="padding:8px 10px;display:none" id="menuBtn"><i class="fa-solid fa-bars"></i></button>
                <div>
                    <h1>{{ $heading ?? 'Bảng điều khiển' }}</h1>
                    <p>{{ $subheading ?? '' }}</p>
                </div>
            </div>
            <div class="search">@yield('topbar-right')</div>
        </header>
        <main class="page-content">@yield('content')</main>
    </div>
    <script>
        function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('sidebarOverlay').classList.toggle('open');}
        window.addEventListener('resize',()=>{document.getElementById('menuBtn').style.display = window.innerWidth <= 768 ? 'inline-flex' : 'none';});
        document.getElementById('menuBtn').style.display = window.innerWidth <= 768 ? 'inline-flex' : 'none';
    </script>
    @stack('scripts')
</body>
</html>
