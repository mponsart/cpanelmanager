<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') — Groupe Speed Cloud</title>
    <style>
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Light.ttf') format('truetype'); font-weight:300; font-style:normal; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Regular.ttf') format('truetype'); font-weight:400; font-style:normal; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-SemiBold.ttf') format('truetype'); font-weight:600; font-style:normal; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Bold.ttf') format('truetype'); font-weight:700; font-style:normal; }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #f5f6fa;
            --bg2:       #ffffff;
            --bg3:       #f0eefc;
            --border:    #e2dff0;
            --accent:    #8a4dfd;
            --accent-h:  #7535f0;
            --accent-bg: rgba(138,77,253,0.08);
            --danger:    #dc3545;
            --warning:   #e09b2d;
            --success:   #198754;
            --text:      #1e1e2f;
            --text-muted:#6c6893;
            --radius:    8px;
            --sidebar-w: 240px;
        }

        body {
            font-family: 'TitilliumWeb', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg2);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 20px 18px 18px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-brand img {
            width: 100%;
            max-width: 170px;
            height: auto;
            display: block;
        }

        .sidebar-brand span {
            color: var(--text-muted);
            font-weight: 400;
            font-size: 11px;
            display: block;
            margin-top: 6px;
            letter-spacing: 0.4px;
        }

        .sidebar-nav {
            padding: 10px 0;
            flex: 1;
        }

        .nav-section {
            padding: 10px 18px 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 18px;
            color: var(--text-muted);
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.15s;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text);
            background: var(--accent-bg);
            border-left-color: var(--accent);
        }

        .nav-link .icon { width: 16px; text-align: center; }

        .sidebar-footer {
            padding: 14px 18px;
            border-top: 1px solid var(--border);
            font-size: 12px;
            color: var(--text-muted);
        }

        .sidebar-footer .user-name {
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
            display: block;
        }

        /* MAIN */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--bg2);
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 600;
        }

        .content {
            padding: 28px;
            flex: 1;
        }

        /* ALERTS */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid;
        }
        .alert-success { background: #e8f5ef; border-color: var(--success); color: #155d38; }
        .alert-error   { background: #fce8ea; border-color: var(--danger);  color: #8b1a2b; }
        .alert-warning { background: #fef5e7; border-color: var(--warning); color: #7a5a1a; }

        /* CARDS */
        .card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        .card-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        /* TABLES */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px 14px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
        }

        tr:hover td { background: var(--bg3); }

        /* FORMS */
        .form-group { margin-bottom: 16px; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            padding: 9px 12px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--accent);
        }

        .form-error {
            color: var(--danger);
            font-size: 12px;
            margin-top: 4px;
        }

        .form-row {
            display: grid;
            gap: 16px;
        }

        .form-row-2 { grid-template-columns: 1fr 1fr; }
        .form-row-3 { grid-template-columns: 1fr 1fr 1fr; }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: var(--radius);
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-primary { background: var(--accent);  color: #fff; }
        .btn-primary:hover { background: var(--accent-h); }
        .btn-danger  { background: var(--danger);  color: #fff; }
        .btn-danger:hover { opacity: 0.85; }
        .btn-ghost   { background: transparent; color: var(--text-muted); border: 1px solid var(--border); }
        .btn-ghost:hover { background: var(--accent-bg); color: var(--text); border-color: var(--accent); }
        .btn-sm      { padding: 5px 12px; font-size: 12px; }

        /* BADGES */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success { background: #e8f5ef; color: var(--success); }
        .badge-error   { background: #fce8ea; color: var(--danger); }
        .badge-warning { background: #fef5e7; color: var(--warning); }
        .badge-muted   { background: var(--bg3); color: var(--text-muted); }
        .badge-accent  { background: var(--accent-bg); color: var(--accent); }

        /* STATS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            transition: border-color 0.15s;
        }
        .stat-card:hover { border-color: var(--accent); }

        .stat-label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-value { font-size: 26px; font-weight: 700; margin-top: 6px; }

        /* PAGINATION */
        .pagination { display: flex; gap: 6px; margin-top: 20px; }
        .pagination a, .pagination span {
            padding: 6px 12px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
        }
        .pagination .active span { background: var(--accent); color: #fff; border-color: var(--accent); }
        .pagination a:hover { background: var(--bg3); color: var(--text); }

        /* MISC */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-header h1 { font-size: 20px; font-weight: 700; }

        .text-muted { color: var(--text-muted); }
        .text-sm    { font-size: 12px; }
        .mt-1       { margin-top: 4px; }
        .mt-2       { margin-top: 8px; }
        .mt-3       { margin-top: 16px; }
        .mb-3       { margin-bottom: 16px; }
        .flex       { display: flex; }
        .gap-2      { gap: 8px; }
        .items-center { align-items: center; }
        .code       { font-family: monospace; font-size: 12px; background: var(--bg3); padding: 2px 6px; border-radius: 4px; }

        /* INPUT GROUP */
        .input-group { display: flex; }
        .input-group input { border-radius: var(--radius) 0 0 var(--radius); border-right: none; flex: 1; min-width: 0; }
        .input-addon {
            background: var(--bg3);
            border: 1px solid var(--border);
            border-left: none;
            border-radius: 0 var(--radius) var(--radius) 0;
            padding: 9px 12px;
            color: var(--text-muted);
            font-size: 13px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            user-select: none;
        }

        /* PASSWORD TOGGLE */
        .pw-wrap { position: relative; }
        .pw-wrap input { padding-right: 38px; }
        .pw-toggle {
            position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--text-muted); padding: 2px; display: flex; align-items: center;
        }
        .pw-toggle:hover { color: var(--text); }

        /* EXTRA BUTTONS */
        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { opacity: 0.85; }
        .btn-warning { background: var(--warning); color: #fff; }
        .btn-warning:hover { opacity: 0.85; }

        /* CARD DEPTH */
        .card { box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
    </style>
    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
        <span>Panel d'administration</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Général</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="1" width="6" height="6" rx="1"/><rect x="9" y="1" width="6" height="6" rx="1"/><rect x="1" y="9" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg></span> Dashboard
        </a>
        <a href="{{ route('logs.index') }}" class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="2" y1="4" x2="14" y2="4"/><line x1="2" y1="8" x2="14" y2="8"/><line x1="2" y1="12" x2="10" y2="12"/></svg></span> Journaux
        </a>
        <a href="{{ route('stats.index') }}" class="nav-link {{ request()->routeIs('stats.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="1,12 5,7 9,9 13,3"/><line x1="1" y1="14" x2="15" y2="14"/></svg></span> Statistiques
        </a>

        <div class="nav-section">cPanel</div>
        <a href="{{ route('email.index') }}" class="nav-link {{ request()->routeIs('email.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="14" height="10" rx="1.5"/><polyline points="1,4 8,9 15,4"/></svg></span> E-mails
        </a>
        <a href="{{ route('database.index') }}" class="nav-link {{ request()->routeIs('database.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><ellipse cx="8" cy="4" rx="6" ry="2"/><path d="M2 4v3c0 1.1 2.7 2 6 2s6-.9 6-2V4"/><path d="M2 7v3c0 1.1 2.7 2 6 2s6-.9 6-2V7"/><path d="M2 10v2c0 1.1 2.7 2 6 2s6-.9 6-2v-2"/></svg></span> Bases de données
        </a>
        <a href="{{ route('domain.index') }}" class="nav-link {{ request()->routeIs('domain.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6.5"/><ellipse cx="8" cy="8" rx="3" ry="6.5"/><line x1="1.5" y1="5.5" x2="14.5" y2="5.5"/><line x1="1.5" y1="10.5" x2="14.5" y2="10.5"/></svg></span> Domaines
        </a>
        <a href="{{ route('ftp.index') }}" class="nav-link {{ request()->routeIs('ftp.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="2" width="14" height="5" rx="1"/><rect x="1" y="9" width="14" height="5" rx="1"/><circle cx="12.5" cy="4.5" r="1" fill="currentColor" stroke="none"/><circle cx="12.5" cy="11.5" r="1" fill="currentColor" stroke="none"/></svg></span> FTP
        </a>
        <a href="{{ route('cron.index') }}" class="nav-link {{ request()->routeIs('cron.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6.5"/><polyline points="8,4.5 8,8 10.5,10"/></svg></span> Cron Jobs
        </a>

        <div class="nav-section">Administration</div>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="5.5" r="3"/><path d="M1.5 14c0-3 3-5.5 6.5-5.5s6.5 2.5 6.5 5.5"/></svg></span> Utilisateurs
        </a>
        <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 1.5L2 4v4c0 3 2.7 5.5 6 7 3.3-1.5 6-4 6-7V4L8 1.5z"/></svg></span> Permissions
        </a>
    </nav>

    <div class="sidebar-footer">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
            @if(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar }}" alt="" style="width:32px;height:32px;border-radius:50%;" referrerpolicy="no-referrer">
            @else
                <div style="width:32px;height:32px;border-radius:50%;background:var(--accent-bg);display:flex;align-items:center;justify-content:center;color:var(--accent);font-weight:700;font-size:14px;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            @endif
            <div>
                <span class="user-name">{{ auth()->user()->name }}</span>
                <span class="text-sm">{{ auth()->user()->email }}</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin-top: 10px;">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;">
                Déconnexion
            </button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<div class="main">
    <div class="topbar">
        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        <span class="text-muted text-sm">{{ now()->format('d/m/Y H:i') }}</span>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script>
(function() {
    var EYE     = '<svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 8c1.5-3.5 4-5.5 7-5.5s5.5 2 7 5.5c-1.5 3.5-4 5.5-7 5.5S2.5 11.5 1 8z"/><circle cx="8" cy="8" r="2.5"/></svg>';
    var EYE_OFF = '<svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 8c1.5-3.5 4-5.5 7-5.5s5.5 2 7 5.5"/><path d="M8 13.5c-3 0-5.5-2-7-5.5"/><line x1="2" y1="2" x2="14" y2="14"/></svg>';
    document.querySelectorAll('input[type="password"]').forEach(function(input) {
        var wrap = document.createElement('div');
        wrap.className = 'pw-wrap';
        input.parentNode.insertBefore(wrap, input);
        wrap.appendChild(input);
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'pw-toggle';
        btn.title = 'Afficher / masquer';
        btn.innerHTML = EYE;
        btn.addEventListener('click', function() {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.innerHTML = show ? EYE_OFF : EYE;
        });
        wrap.appendChild(btn);
    });
})();
</script>
@stack('scripts')
</body>
</html>
