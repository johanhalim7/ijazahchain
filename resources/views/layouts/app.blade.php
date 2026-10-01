<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'IjazahChain')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root{--ic-primary:#0f766e;--ic-accent:#2563eb;--ic-ink:#111827;--ic-muted:#6b7280;--ic-soft:#f6f8fb;--ic-line:#e5e7eb}
        body{background:var(--ic-soft);color:var(--ic-ink);font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif}
        .navbar{background:#fff;border-bottom:1px solid var(--ic-line)}
        .brand-mark{width:36px;height:36px;border-radius:8px;background:linear-gradient(135deg,var(--ic-primary),var(--ic-accent));display:inline-flex;align-items:center;justify-content:center;color:#fff}
        .app-shell{display:grid;grid-template-columns:260px minmax(0,1fr);min-height:calc(100vh - 65px)}
        .sidebar{background:#fff;border-right:1px solid var(--ic-line);padding:22px}
        .side-link{display:flex;gap:10px;align-items:center;padding:11px 13px;border-radius:8px;color:#374151;text-decoration:none;margin-bottom:6px}
        .side-link:hover,.side-link.active{background:#eaf7f5;color:var(--ic-primary)}
        .content{padding:0}
        .content,.guest-main{min-height:calc(100vh - 65px);display:flex;flex-direction:column}
        .card{border:1px solid var(--ic-line);border-radius:8px;box-shadow:0 10px 24px rgba(15,23,42,.05)}
        .btn-primary{background:var(--ic-primary);border-color:var(--ic-primary)}
        .btn-outline-primary{color:var(--ic-primary);border-color:var(--ic-primary)}
        .badge-soft{background:#eaf7f5;color:var(--ic-primary)}
        .status-Draft{background:#eef2ff;color:#3730a3}.status-Pending{background:#fff7ed;color:#9a3412}.status-Aktif{background:#ecfdf5;color:#047857}.status-Ditolak{background:#fef2f2;color:#b91c1c}.status-Revoked{background:#fef2f2;color:#b91c1c}
        .hero{background:linear-gradient(135deg,#063f3a,#1d4ed8);color:#fff;padding:54px 0}
        .hero .verify-card{background:#fff;color:var(--ic-ink);border-radius:8px;padding:22px;box-shadow:0 20px 50px rgba(0,0,0,.18)}
        .step-dot{width:34px;height:34px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;background:#eaf7f5;color:var(--ic-primary);font-weight:700}
        .hash-box{font-family:ui-monospace,SFMono-Regular,Consolas,monospace;word-break:break-all;background:#f8fafc;border:1px solid var(--ic-line);border-radius:8px;padding:12px}
        .toast-container{z-index:1080}
        .app-toast{border:1px solid var(--ic-line);border-radius:8px;box-shadow:0 18px 45px rgba(15,23,42,.18)}
        
        /* Redesign UI Enhancements */
        .form-control-lg { font-size: 1rem !important; }
        ::placeholder { font-size: 1rem !important; font-weight: 400; opacity: 0.6 !important; }
        input[type="date"]:invalid::-webkit-datetime-edit { color: var(--ic-ink); font-size: 1rem !important; font-weight: 400; opacity: 0.6; }
        input[type="date"]::-webkit-inner-spin-button, input[type="date"]::-webkit-calendar-picker-indicator { opacity: 0.5; cursor: pointer; }
        .glass-panel { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.4); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); }
        .hover-lift { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(15, 23, 42, 0.1); }
        .bg-glow { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.6; z-index: 0; pointer-events: none; }
        .glow-primary { background: var(--ic-primary); width: 300px; height: 300px; top: -100px; right: -50px; }
        .glow-accent { background: var(--ic-accent); width: 400px; height: 400px; bottom: -150px; left: -100px; opacity: 0.4; }
        .animate-float { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-15px); } 100% { transform: translateY(0px); } }
        
        @media (max-width: 991px){.app-shell{grid-template-columns:1fr}.sidebar{border-right:0;border-bottom:1px solid var(--ic-line)}.content{padding:18px}}
    </style>
    @stack('head')
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('home') }}">
            <span class="brand-mark"><i class="bi bi-shield-lock"></i></span> IjazahChain
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topnav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="topnav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('verify.form') }}">Verifikasi</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item">
                        <form method="post" action="{{ route('logout') }}">@csrf<button class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-right"></i> Logout</button></form>
                    </li>
                @else
                    <li class="nav-item"><a class="btn btn-sm btn-primary" href="{{ route('login') }}"><i class="bi bi-person-lock"></i> Masuk</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@auth
<div class="app-shell">
    <aside class="sidebar">
        <div class="small text-uppercase text-secondary fw-semibold mb-2">{{ auth()->user()->role }}</div>
        <div class="fw-bold mb-3">{{ auth()->user()->nama }}</div>
        <a class="side-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid"></i> Dashboard</a>
        <a class="side-link {{ request()->routeIs('wallet.*') ? 'active' : '' }}" href="{{ route('wallet.show') }}"><i class="bi bi-wallet2"></i> Wallet Saya</a>
        <a class="side-link {{ request()->routeIs('ijazahs.*') && !request()->routeIs('ijazahs.create') ? 'active' : '' }}" href="{{ route('ijazahs.index') }}"><i class="bi bi-mortarboard"></i> Data Ijazah</a>
        @if(auth()->user()->role === 'admin')
            <div class="small text-uppercase text-secondary fw-semibold mb-1 mt-3">Admin Panel</div>
            <a class="side-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="bi bi-people"></i> Manajemen User</a>
            <a class="side-link {{ request()->routeIs('workflows.*') ? 'active' : '' }}" href="{{ route('workflows.index') }}"><i class="bi bi-diagram-3"></i> Manajemen Workflow</a>
            <div class="small text-uppercase text-secondary fw-semibold mb-1 mt-3">Menu Utama</div>
        @endif
        @if(auth()->user()->role === 'rektor')
            <a class="side-link {{ request()->routeIs('rector-decisions.*') ? 'active' : '' }}" href="{{ route('rector-decisions.index') }}"><i class="bi bi-check-circle"></i> Riwayat Keputusan</a>
        @endif
        @if(auth()->user()->role === 'akademik')
            <a class="side-link {{ request()->routeIs('ijazahs.create') ? 'active' : '' }}" href="{{ route('ijazahs.create') }}"><i class="bi bi-plus-circle"></i> Buat Ijazah</a>
        @endif
        <a class="side-link {{ request()->routeIs('revoke.*') ? 'active' : '' }}" href="{{ route('revoke.index') }}"><i class="bi bi-slash-circle"></i> Pencabutan (Revoke)</a>
        <a class="side-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}" href="{{ route('activity-logs.index') }}"><i class="bi bi-clock-history"></i> Activity Log</a>
        <a class="side-link {{ request()->routeIs('verification-logs.*') ? 'active' : '' }}" href="{{ route('verification-logs.index') }}"><i class="bi bi-search"></i> Verification Log</a>
        <a class="side-link {{ request()->routeIs('verify.*') ? 'active' : '' }}" href="{{ route('verify.form') }}"><i class="bi bi-qr-code-scan"></i> Verifikasi Publik</a>
    </aside>
    <main class="content">
@else
    <main class="guest-main">
@endauth
        @auth<div class="flex-grow-1 p-4" style="padding: 28px !important;">@endauth
        @yield('content')
        @auth</div>@endauth
        
        <footer class="site-footer mt-auto border-top py-4 bg-white {{ auth()->check() ? 'px-4' : '' }}">
            <div class="{{ auth()->check() ? '' : 'container' }}">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 small text-secondary">
                    <div class="d-flex align-items-center gap-2 fw-semibold text-dark">
                        <span class="d-inline-flex align-items-center justify-content-center text-white shadow-sm" style="width: 28px; height: 28px; border-radius: 6px; background: linear-gradient(135deg, var(--ic-primary), var(--ic-accent));">
                            <i class="bi bi-shield-lock" style="font-size: 0.85rem;"></i>
                        </span>
                        <span>IjazahChain &copy; {{ date('Y') }}</span>
                    </div>
                    <div class="fw-medium">
                        <i class="bi bi-hdd-network text-primary me-1"></i> <span class="opacity-75">Secured via Ethereum Network</span>
                    </div>
                </div>
            </div>
        </footer>
    </main>
@auth</div>@endauth

@if(session('success') || $errors->any())
<div class="toast-container position-fixed top-0 end-0 p-3">
    @if(session('success'))
        <div class="toast app-toast align-items-center text-bg-success border-0" role="status" aria-live="polite" aria-atomic="true" data-bs-delay="4200">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="toast app-toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="6000">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
            </div>
        </div>
    @endif
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.toast').forEach((toastEl) => {
    bootstrap.Toast.getOrCreateInstance(toastEl).show();
});
</script>
@stack('scripts')
</body>
</html>
