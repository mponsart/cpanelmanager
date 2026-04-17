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
            --bg: #f1f5f9;
            --panel: #ffffff;
            --panel-soft: #f8fafc;
            --panel-alt: #eff6ff;
            --border: #e2e8f0;
            --border-strong: #cbd5e1;
            --text: #0f172a;
            --text-muted: #64748b;
            --accent: #2563eb;
            --accent-strong: #1d4ed8;
            --accent-soft: rgba(37, 99, 235, 0.10);
            --danger: #dc2626;
            --warning: #d97706;
            --success: #16a34a;
            --radius: 10px;
            --sidebar-w: 260px;
            --sidebar-bg: #0f172a;
            --sidebar-border: rgba(255,255,255,0.07);
            --sidebar-text: #94a3b8;
            --sidebar-text-hover: #e2e8f0;
            --sidebar-active-bg: rgba(37,99,235,0.18);
            --sidebar-active-border: rgba(37,99,235,0.45);
            --shadow-sm: 0 1px 3px rgba(15,23,42,0.08), 0 1px 2px rgba(15,23,42,0.06);
            --shadow-md: 0 4px 16px rgba(15,23,42,0.12);
        }

        body {
            font-family: 'TitilliumWeb', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            color: var(--text);
            min-height: 100vh;
            line-height: 1.5;
            background: var(--bg);
        }

        .app-shell { display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: 100;
            transition: transform 0.22s cubic-bezier(.4,0,.2,1);
        }

        .sidebar-brand {
            padding: 20px 16px 16px;
            border-bottom: 1px solid var(--sidebar-border);
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 64px;
        }

        .sidebar-brand .sidebar-close { position: absolute; top: 12px; right: 12px; }
        .sidebar-brand img { width: 130px; height: auto; display: block; }
        .sidebar-brand span {
            color: var(--sidebar-text);
            font-weight: 500;
            font-size: 11px;
            display: block;
            margin-top: 2px;
            letter-spacing: 0.3px;
        }

        .sidebar-nav { padding: 12px 8px; flex: 1; }

        .nav-section {
            margin: 18px 8px 6px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(148, 163, 184, 0.5);
        }

        .nav-section:first-child { margin-top: 4px; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 10px;
            margin-bottom: 1px;
            border-radius: 8px;
            color: var(--sidebar-text);
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.12s ease;
            font-weight: 500;
            font-size: 13.5px;
        }

        .nav-link .icon {
            width: 22px;
            height: 22px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            flex-shrink: 0;
            transition: inherit;
        }

        .nav-link:hover {
            color: var(--sidebar-text-hover);
            background: rgba(255, 255, 255, 0.06);
        }

        .nav-link:hover .icon { color: #94a3b8; }

        .nav-link.active {
            color: #eff6ff;
            background: var(--sidebar-active-bg);
            border-color: var(--sidebar-active-border);
        }

        .nav-link.active .icon { color: #93c5fd; }

        .sidebar-footer {
            margin: 8px;
            border: 1px solid var(--sidebar-border);
            background: rgba(255, 255, 255, 0.04);
            border-radius: 10px;
            padding: 12px;
            font-size: 12px;
            color: var(--sidebar-text);
        }

        .sidebar-footer .user-name { font-weight: 600; color: #e2e8f0; margin-bottom: 1px; display: block; font-size: 13px; }

        /* ── MAIN ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(8px);
            padding: 0 24px;
            height: 56px;
            display: grid;
            grid-template-columns: auto minmax(200px, 400px) auto;
            align-items: center;
            gap: 14px;
        }

        .topbar-left { display: flex; align-items: center; gap: 10px; }

        .menu-toggle, .sidebar-close {
            display: none;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--panel);
            color: var(--text-muted);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.12s;
        }

        .menu-toggle:hover, .sidebar-close:hover {
            background: var(--panel-alt);
            border-color: #bfdbfe;
            color: var(--accent);
        }

        .topbar-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
            letter-spacing: -0.1px;
        }

        .topbar-search {
            position: relative;
            display: flex;
            align-items: center;
        }

        .topbar-search svg {
            position: absolute;
            left: 11px;
            color: #94a3b8;
            pointer-events: none;
        }

        .topbar-search input {
            width: 100%;
            background: var(--panel-soft);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            padding: 8px 10px 8px 34px;
            font-size: 13px;
            outline: none;
            transition: all 0.12s;
        }

        .topbar-search input:focus {
            background: #fff;
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .content { padding: 24px; flex: 1; }

        /* ── ALERTS ── */
        .alert {
            padding: 11px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13.5px;
            border: 1px solid;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .alert-success { background: #f0fdf4; border-color: #bbf7d0; color: #15803d; }
        .alert-error   { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }
        .alert-warning { background: #fffbeb; border-color: #fde68a; color: #92400e; }

        /* ── CARDS ── */
        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 18px;
            box-shadow: var(--shadow-sm);
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── TABLES ── */
        .table-wrap { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; min-width: 620px; }

        th, td {
            padding: 10px 14px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        th {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--text-muted);
            background: var(--panel-soft);
        }

        tbody tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }

        /* ── FORMS ── */
        .form-group { margin-bottom: 14px; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 6px;
            letter-spacing: 0.2px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="date"],
        input[type="search"],
        select,
        textarea {
            width: 100%;
            background: var(--panel);
            border: 1px solid var(--border-strong);
            border-radius: 8px;
            color: var(--text);
            padding: 9px 12px;
            font-size: 13.5px;
            outline: none;
            transition: all 0.12s;
            font-family: inherit;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
            background: #fff;
        }

        .form-error { color: var(--danger); font-size: 12px; margin-top: 4px; }
        .form-row { display: grid; gap: 14px; }
        .form-row-2 { grid-template-columns: 1fr 1fr; }
        .form-row-3 { grid-template-columns: 1fr 1fr 1fr; }
        .cron-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.12s;
            white-space: nowrap;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        .btn-primary:hover {
            background: var(--accent-strong);
            border-color: var(--accent-strong);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.30);
        }

        .btn-danger  { background: var(--danger); color: #fff; border-color: var(--danger); }
        .btn-danger:hover { background: #b91c1c; border-color: #b91c1c; }
        .btn-success { background: var(--success); color: #fff; border-color: var(--success); }
        .btn-success:hover { opacity: 0.9; }
        .btn-warning { background: var(--warning); color: #fff; border-color: var(--warning); }
        .btn-warning:hover { opacity: 0.9; }

        .btn-ghost {
            background: var(--panel);
            color: var(--text-muted);
            border-color: var(--border-strong);
        }

        .btn-ghost:hover {
            background: var(--panel-soft);
            color: var(--text);
            border-color: #93c5fd;
        }

        .btn-sm { padding: 5px 11px; font-size: 12px; }

        /* ── BADGES ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .badge-success { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
        .badge-error   { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
        .badge-warning { background: #fffbeb; color: #92400e; border-color: #fde68a; }
        .badge-muted   { background: var(--panel-soft); color: var(--text-muted); border-color: var(--border); }
        .badge-accent  { background: var(--accent-soft); color: var(--accent); border-color: #bfdbfe; }

        /* ── STATS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px 20px;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 12px;
            bottom: 12px;
            width: 3px;
            border-radius: 0 2px 2px 0;
            background: var(--accent);
        }

        .stat-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding-left: 12px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-top: 6px;
            line-height: 1.15;
            padding-left: 12px;
            color: var(--text);
        }

        /* ── PAGINATION ── */
        .pagination {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 18px;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: 6px 11px;
            border-radius: 7px;
            border: 1px solid var(--border);
            color: var(--text-muted);
            background: var(--panel);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
        }

        .pagination .active { background: var(--accent); color: #fff; border-color: var(--accent); }
        .pagination a:hover { background: var(--panel-alt); color: var(--accent); border-color: #bfdbfe; }
        .pagination .disabled { opacity: 0.4; cursor: default; }

        /* ── PAGE HEADER ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px;
            flex-wrap: wrap;
        }

        .page-header h1 { font-size: 20px; font-weight: 700; letter-spacing: -0.2px; }

        /* ── MISC ── */
        .text-muted { color: var(--text-muted); }
        .text-sm { font-size: 12px; }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 16px; }
        .mb-3 { margin-bottom: 16px; }
        .flex { display: flex; }
        .gap-2 { gap: 8px; }
        .items-center { align-items: center; }

        .code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', monospace;
            font-size: 12px;
            background: #f1f5f9;
            color: #1d4ed8;
            padding: 2px 7px;
            border-radius: 5px;
            border: 1px solid #dbeafe;
        }

        .input-group { display: flex; }

        .input-group input {
            border-radius: 8px 0 0 8px;
            border-right: none;
            flex: 1;
            min-width: 0;
        }

        .input-addon {
            background: var(--panel-soft);
            border: 1px solid var(--border-strong);
            border-left: none;
            border-radius: 0 8px 8px 0;
            padding: 9px 12px;
            color: var(--text-muted);
            font-size: 13px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            user-select: none;
        }

        .pw-wrap { position: relative; }
        .pw-wrap input { padding-right: 40px; }

        .pw-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 2px;
            display: flex;
            align-items: center;
        }

        .pw-toggle:hover { color: var(--accent); }

        .inline-form {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .inline-form .form-group { flex: 1; margin-bottom: 0; min-width: 260px; }
        .disk-usage-row { display: flex; align-items: center; gap: 20px; padding: 8px 0; }
        .disk-usage-details { flex: 1; }
        .disk-usage-bar-wrap { flex: 1; max-width: 200px; }
        .domain-summary { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .back-link-wrap { margin-bottom: 18px; }

        .back-link {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
        }

        .back-link:hover { color: var(--accent-strong); text-decoration: underline; }

        :focus-visible {
            outline: 2px solid rgba(37, 99, 235, 0.45);
            outline-offset: 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; scroll-behavior: auto !important; }
        }

        /* ── OVERLAY ── */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            z-index: 90;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
        }

        body.nav-open .sidebar-overlay { opacity: 1; visibility: visible; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1200px) {
            .cron-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .topbar { grid-template-columns: auto minmax(160px, 1fr) auto; }
        }

        @media (max-width: 1100px) {
            .content { padding: 20px; }
        }

        @media (max-width: 1024px) {
            .menu-toggle, .sidebar-close { display: inline-flex; }

            .sidebar {
                transform: translateX(-100%);
                box-shadow: var(--shadow-md);
                width: min(88vw, 300px);
            }

            body.nav-open .sidebar { transform: translateX(0); }
            .main { margin-left: 0; }
            .topbar { grid-template-columns: 1fr; }
            .topbar-right { justify-content: space-between; }
        }

        @media (max-width: 900px) {
            .form-row,
            .form-row-2,
            .form-row-3 { grid-template-columns: 1fr !important; }

            .stats-grid { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); }

            .input-group { flex-wrap: wrap; }

            .input-group input {
                border-radius: 8px;
                border-right: 1px solid var(--border-strong);
                margin-bottom: 8px;
            }

            .input-addon {
                border: 1px solid var(--border-strong);
                border-radius: 8px;
            }

            .disk-usage-row,
            .domain-summary {
                flex-direction: column;
                align-items: flex-start;
            }

            .disk-usage-bar-wrap { max-width: none; width: 100%; }
            .inline-form .form-group { min-width: 100%; }
            .btn { width: 100%; }
            .table-wrap .btn { width: auto; }
            .pagination { gap: 3px; }
            .pagination a, .pagination span { padding: 5px 9px; }
        }

        @media (max-width: 640px) {
            .content { padding: 12px; }
            .topbar { padding: 0 12px; }
            .topbar-title { font-size: 14px; }
            .card { padding: 14px; }
            th, td { padding: 8px 10px; }
            .stat-value { font-size: 22px; }
            .code { font-size: 11px; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="app-shell">

<aside class="sidebar">
    <div class="sidebar-brand">
        <button type="button" class="sidebar-close" data-sidebar-close aria-label="Fermer le menu">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="3" y1="3" x2="13" y2="13"/><line x1="13" y1="3" x2="3" y2="13"/></svg>
        </button>
        <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
        <span>Panel d'administration</span>
    </div>

    @php
        $navUser  = auth()->user();
        $navPerms = $navUser->isSuperAdmin()
            ? null  // null means "all permissions granted"
            : $navUser->permissions()->pluck('key')->toArray();
        $navCan   = fn(string $p) => $navPerms === null || in_array($p, $navPerms, true);

        $hasCpanelAccess = $navCan('view_email')
                        || $navCan('view_db')
                        || $navCan('view_domain')
                        || $navCan('view_ftp')
                        || $navCan('manage_cron')
                        || $navCan('view_associations')
                        || $navCan('access_cpanel');
        $hasAdminAccess  = $navCan('manage_users');
    @endphp

    <nav class="sidebar-nav">
        <div class="nav-section">Général</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="1" width="6" height="6" rx="1"/><rect x="9" y="1" width="6" height="6" rx="1"/><rect x="1" y="9" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg></span> Dashboard
        </a>
        <a href="{{ route('logs.index') }}" class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="2" y1="4" x2="14" y2="4"/><line x1="2" y1="8" x2="14" y2="8"/><line x1="2" y1="12" x2="10" y2="12"/></svg></span> Journaux
        </a>
        @if($navCan('view_stats'))
        <a href="{{ route('stats.index') }}" class="nav-link {{ request()->routeIs('stats.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="1,12 5,7 9,9 13,3"/><line x1="1" y1="14" x2="15" y2="14"/></svg></span> Statistiques
        </a>
        @endif

        @if($hasCpanelAccess)
        <div class="nav-section">cPanel</div>
        @if($navCan('view_email'))
        <a href="{{ route('email.index') }}" class="nav-link {{ request()->routeIs('email.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="14" height="10" rx="1.5"/><polyline points="1,4 8,9 15,4"/></svg></span> E-mails
        </a>
        @endif
        @if($navCan('view_db'))
        <a href="{{ route('database.index') }}" class="nav-link {{ request()->routeIs('database.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><ellipse cx="8" cy="4" rx="6" ry="2"/><path d="M2 4v3c0 1.1 2.7 2 6 2s6-.9 6-2V4"/><path d="M2 7v3c0 1.1 2.7 2 6 2s6-.9 6-2V7"/><path d="M2 10v2c0 1.1 2.7 2 6 2s6-.9 6-2v-2"/></svg></span> Bases de données
        </a>
        @endif
        @if($navCan('view_domain'))
        <a href="{{ route('domain.index') }}" class="nav-link {{ request()->routeIs('domain.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6.5"/><ellipse cx="8" cy="8" rx="3" ry="6.5"/><line x1="1.5" y1="5.5" x2="14.5" y2="5.5"/><line x1="1.5" y1="10.5" x2="14.5" y2="10.5"/></svg></span> Domaines
        </a>
        @endif
        @if($navCan('view_ftp'))
        <a href="{{ route('ftp.index') }}" class="nav-link {{ request()->routeIs('ftp.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="2" width="14" height="5" rx="1"/><rect x="1" y="9" width="14" height="5" rx="1"/><circle cx="12.5" cy="4.5" r="1" fill="currentColor" stroke="none"/><circle cx="12.5" cy="11.5" r="1" fill="currentColor" stroke="none"/></svg></span> FTP
        </a>
        @endif
        @if($navCan('manage_cron'))
        <a href="{{ route('cron.index') }}" class="nav-link {{ request()->routeIs('cron.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6.5"/><polyline points="8,4.5 8,8 10.5,10"/></svg></span> Cron Jobs
        </a>
        @endif
        @if($navCan('view_associations'))
        <a href="{{ route('association.index') }}" class="nav-link {{ request()->routeIs('association.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 3.5A1.5 1.5 0 013.5 2h3l1.5 2h4.5A1.5 1.5 0 0114 5.5v7a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 012 12.5v-9z"/></svg></span> Associations
        </a>
        @endif
        @if($navCan('access_cpanel'))
        <a href="{{ route('cpanel.index') }}" class="nav-link {{ request()->routeIs('cpanel.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1v-3"/><polyline points="9,1 15,1 15,7"/><line x1="15" y1="1" x2="7" y2="9"/></svg></span> Accès cPanel
        </a>
        @endif
        @endif

        @if($hasAdminAccess)
        <div class="nav-section">Administration</div>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="5.5" r="3"/><path d="M1.5 14c0-3 3-5.5 6.5-5.5s6.5 2.5 6.5 5.5"/></svg></span> Utilisateurs
        </a>
        <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 1.5L2 4v4c0 3 2.7 5.5 6 7 3.3-1.5 6-4 6-7V4L8 1.5z"/></svg></span> Permissions
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
            <div style="width:32px;height:32px;border-radius:8px;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">{{ auth()->user()->initials() }}</div>
            <div style="min-width:0;">
                <span class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">{{ auth()->user()->name }}</span>
                <span class="text-sm" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">{{ auth()->user()->email }}</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;background:rgba(255,255,255,0.06);color:#94a3b8;border-color:rgba(255,255,255,0.1);">
                Déconnexion
            </button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div class="topbar-left">
            <button type="button" class="menu-toggle" data-sidebar-open aria-label="Ouvrir le menu">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="2" y1="4" x2="14" y2="4"/><line x1="2" y1="8" x2="14" y2="8"/><line x1="2" y1="12" x2="14" y2="12"/></svg>
            </button>
            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        </div>

        <div class="topbar-search">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="7" cy="7" r="4.5"/><line x1="10.5" y1="10.5" x2="14" y2="14"/></svg>
            <input type="search" placeholder="Rechercher un module dans le menu…" data-nav-search aria-label="Rechercher un module dans le menu">
        </div>

        <div class="topbar-right">
            <a href="{{ route('logs.index') }}" class="btn btn-ghost btn-sm" style="font-size:12px;">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6.5"/><line x1="8" y1="5" x2="8" y2="8"/><line x1="8" y1="11" x2="8.01" y2="11" stroke-width="2"/></svg>
                Incidents
            </a>
            <span class="text-muted text-sm" style="white-space:nowrap;">{{ now()->format('d/m/Y H:i') }}</span>
        </div>
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

<div class="sidebar-overlay" data-sidebar-overlay></div>

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
(function() {
    var body = document.body;
    var openBtn = document.querySelector('[data-sidebar-open]');
    var closeBtn = document.querySelector('[data-sidebar-close]');
    var overlay = document.querySelector('[data-sidebar-overlay]');
    if (!openBtn || !closeBtn || !overlay) return;
    function closeNav() { body.classList.remove('nav-open'); }
    function openNav() { body.classList.add('nav-open'); }
    openBtn.addEventListener('click', openNav);
    closeBtn.addEventListener('click', closeNav);
    overlay.addEventListener('click', closeNav);
    document.querySelectorAll('.sidebar .nav-link').forEach(function(link) {
        link.addEventListener('click', closeNav);
    });
})();
(function() {
    var input = document.querySelector('[data-nav-search]');
    if (!input) return;
    var links = Array.from(document.querySelectorAll('.sidebar .nav-link'));
    input.addEventListener('input', function() {
        var q = input.value.toLowerCase().trim();
        links.forEach(function(link) {
            var text = link.textContent.toLowerCase();
            link.style.display = !q || text.includes(q) ? '' : 'none';
        });
    });
})();
</script>
@stack('scripts')
</body>
</html>
