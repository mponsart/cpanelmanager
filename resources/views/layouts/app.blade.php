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
            --bg: #f6f8fc;
            --panel: #ffffff;
            --panel-soft: #f9fbff;
            --panel-alt: #eef3ff;
            --border: #dbe4f2;
            --text: #0f1b33;
            --text-muted: #6f7f99;
            --accent: #2f63d9;
            --accent-strong: #234fb2;
            --accent-soft: rgba(47, 99, 217, 0.10);
            --danger: #dc3545;
            --warning: #df8b15;
            --success: #198754;
            --radius: 14px;
            --sidebar-w: 284px;
            --shadow-sm: 0 8px 26px rgba(18, 37, 71, 0.08);
            --shadow-md: 0 16px 40px rgba(18, 37, 71, 0.12);
        }

        body {
            font-family: 'TitilliumWeb', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            color: var(--text);
            min-height: 100vh;
            line-height: 1.45;
            background:
                radial-gradient(circle at 0% 0%, rgba(47, 99, 217, 0.14), transparent 35%),
                radial-gradient(circle at 100% 0%, rgba(17, 135, 212, 0.12), transparent 28%),
                var(--bg);
        }

        .app-shell { display: flex; min-height: 100vh; }

        .sidebar {
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #111f3d 0%, #0c1730 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: 100;
            transition: transform 0.25s ease;
            box-shadow: 12px 0 34px rgba(9, 17, 37, 0.28);
        }

        .sidebar-brand {
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.09);
            position: relative;
            background: linear-gradient(165deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
        }

        .sidebar-brand .sidebar-close { position: absolute; top: 12px; right: 12px; }
        .sidebar-brand img { width: 100%; max-width: 182px; height: auto; display: block; filter: brightness(1.05); }
        .sidebar-brand span {
            color: rgba(226, 236, 255, 0.72);
            font-weight: 600;
            font-size: 11px;
            display: block;
            margin-top: 8px;
            letter-spacing: 0.5px;
        }

        .sidebar-nav { padding: 14px 10px; flex: 1; }

        .nav-section {
            margin: 14px 10px 8px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(193, 208, 235, 0.6);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            margin-bottom: 5px;
            border-radius: 10px;
            color: rgba(220, 233, 255, 0.86);
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.15s ease;
            font-weight: 600;
        }

        .nav-link .icon {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.09);
            color: #e5efff;
            transition: inherit;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.16);
        }

        .nav-link:hover .icon,
        .nav-link.active .icon {
            color: #fff;
            background: linear-gradient(145deg, var(--accent), #3b7af8);
            box-shadow: 0 6px 14px rgba(32, 109, 255, 0.35);
        }

        .sidebar-footer {
            margin: 12px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 12px;
            font-size: 12px;
            color: rgba(220, 233, 255, 0.82);
        }

        .sidebar-footer .user-name { font-weight: 700; color: #fff; margin-bottom: 2px; display: block; }

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
            background: rgba(246, 248, 252, 0.88);
            border-bottom: 1px solid rgba(146, 166, 203, 0.38);
            backdrop-filter: blur(12px);
            padding: 14px 24px;
            display: grid;
            grid-template-columns: auto minmax(220px, 460px) auto;
            align-items: center;
            gap: 14px;
        }

        .topbar-left { display: flex; align-items: center; gap: 12px; }

        .menu-toggle, .sidebar-close {
            display: none;
            width: 38px;
            height: 38px;
            border-radius: 11px;
            border: 1px solid var(--border);
            background: var(--panel);
            color: var(--text);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }

        .menu-toggle:hover, .sidebar-close:hover {
            background: var(--panel-alt);
            border-color: #b4c6e8;
            color: var(--accent-strong);
        }

        .topbar-title { font-size: 20px; font-weight: 700; letter-spacing: 0.2px; }

        .topbar-search {
            position: relative;
            display: flex;
            align-items: center;
        }

        .topbar-search svg {
            position: absolute;
            left: 12px;
            color: #7f90b1;
            pointer-events: none;
        }

        .topbar-search input {
            width: 100%;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 11px;
            color: var(--text);
            padding: 10px 12px 10px 36px;
            font-size: 13px;
            outline: none;
            transition: all 0.15s;
        }

        .topbar-search input:focus {
            border-color: #8fb0ea;
            box-shadow: 0 0 0 4px rgba(47, 99, 217, 0.12);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .content { padding: 24px; flex: 1; }

        .alert {
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 14px;
            border: 1px solid;
            box-shadow: var(--shadow-sm);
        }

        .alert-success { background: #e8f5ef; border-color: #b8e5c9; color: #145f37; }
        .alert-error   { background: #fdebec; border-color: #f3b6bd; color: #8b1a2b; }
        .alert-warning { background: #fff5e8; border-color: #f4d6a8; color: #7a5a1a; }

        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e7edf8;
        }

        .table-wrap { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; min-width: 620px; }

        th, td {
            padding: 11px 14px;
            text-align: left;
            border-bottom: 1px solid #edf2fb;
            vertical-align: middle;
        }

        th {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #7a8aa7;
            background: #f7faff;
        }

        tr:hover td { background: #f7faff; }

        .form-group { margin-bottom: 16px; }

        label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #7d8fab;
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
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
            background: var(--panel-soft);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            padding: 10px 12px;
            font-size: 14px;
            outline: none;
            transition: all 0.15s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #8fb0ea;
            box-shadow: 0 0 0 4px rgba(47, 99, 217, 0.12);
            background: #fff;
        }

        .form-error { color: var(--danger); font-size: 12px; margin-top: 4px; }
        .form-row { display: grid; gap: 16px; }
        .form-row-2 { grid-template-columns: 1fr 1fr; }
        .form-row-3 { grid-template-columns: 1fr 1fr 1fr; }
        .cron-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 16px;
            border-radius: 11px;
            font-size: 13px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(145deg, var(--accent), #3f74ea);
            color: #fff;
            box-shadow: 0 8px 16px rgba(47, 99, 217, 0.28);
        }

        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 12px 20px rgba(47, 99, 217, 0.34); }
        .btn-danger  { background: var(--danger); color: #fff; }
        .btn-danger:hover, .btn-success:hover, .btn-warning:hover { opacity: 0.9; }

        .btn-ghost {
            background: #fff;
            color: var(--text-muted);
            border: 1px solid var(--border);
        }

        .btn-ghost:hover { background: var(--panel-alt); color: var(--text); border-color: #b5c8e7; }
        .btn-sm { padding: 7px 12px; font-size: 12px; }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .badge-success { background: #e8f5ef; color: var(--success); border-color: #bee6cd; }
        .badge-error   { background: #fdebec; color: var(--danger); border-color: #f1bfc4; }
        .badge-warning { background: #fff4df; color: var(--warning); border-color: #f2d6a6; }
        .badge-muted   { background: var(--panel-soft); color: var(--text-muted); border-color: #e2eaf7; }
        .badge-accent  { background: var(--accent-soft); color: var(--accent); border-color: #bad0f6; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), rgba(47, 99, 217, 0.1));
        }

        .stat-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.7px; }
        .stat-value { font-size: 30px; font-weight: 700; margin-top: 6px; line-height: 1.15; }

        .pagination {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: 7px 12px;
            border-radius: 10px;
            border: 1px solid var(--border);
            color: var(--text-muted);
            background: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .pagination .active { background: linear-gradient(145deg, var(--accent), #3f74ea); color: #fff; border-color: var(--accent); }
        .pagination a:hover { background: var(--panel-alt); color: var(--text); border-color: #b4c6e8; }
        .pagination .disabled { opacity: 0.4; cursor: default; }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 10px;
            flex-wrap: wrap;
        }

        .page-header h1 { font-size: 22px; font-weight: 700; }

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
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
            font-size: 12px;
            background: #edf3ff;
            color: #2f63d9;
            padding: 3px 7px;
            border-radius: 8px;
            border: 1px solid #cdddf9;
        }

        .input-group { display: flex; }

        .input-group input {
            border-radius: 12px 0 0 12px;
            border-right: none;
            flex: 1;
            min-width: 0;
        }

        .input-addon {
            background: var(--panel-soft);
            border: 1px solid var(--border);
            border-left: none;
            border-radius: 0 12px 12px 0;
            padding: 10px 12px;
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

        .pw-toggle:hover { color: var(--accent-strong); }

        .btn-success { background: var(--success); color: #fff; }
        .btn-warning { background: var(--warning); color: #fff; }

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
        .back-link-wrap { margin-bottom: 20px; }

        .back-link {
            color: var(--accent-strong);
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(7, 12, 26, 0.55);
            z-index: 90;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
        }

        body.nav-open .sidebar-overlay { opacity: 1; visibility: visible; }

        @media (max-width: 1200px) {
            .cron-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .topbar { grid-template-columns: auto minmax(170px, 1fr) auto; }
        }

        @media (max-width: 1100px) {
            .content { padding: 20px; }
            .topbar { padding: 12px 20px; }
        }

        @media (max-width: 1024px) {
            .menu-toggle, .sidebar-close { display: inline-flex; }

            .sidebar {
                transform: translateX(-100%);
                box-shadow: var(--shadow-md);
                width: min(88vw, 320px);
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

            .stats-grid { grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); }

            .input-group { flex-wrap: wrap; }

            .input-group input {
                border-radius: 12px;
                border-right: 1px solid var(--border);
                margin-bottom: 8px;
            }

            .input-addon {
                border: 1px solid var(--border);
                border-radius: 12px;
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
            .pagination { gap: 4px; }
            .pagination a, .pagination span { padding: 6px 10px; }
        }

        @media (max-width: 640px) {
            .content { padding: 14px; }
            .topbar { padding: 10px 14px; }
            .topbar-title { font-size: 17px; }
            .card { padding: 14px; }
            th, td { padding: 9px 10px; }
            .stat-value { font-size: 24px; }
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
        <a href="{{ route('association.index') }}" class="nav-link {{ request()->routeIs('association.*') ? 'active' : '' }}">
            <span class="icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 3.5A1.5 1.5 0 013.5 2h3l1.5 2h4.5A1.5 1.5 0 0114 5.5v7a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 012 12.5v-9z"/></svg></span> Associations
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
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(145deg,var(--accent),#3f74ea);color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;box-shadow:0 8px 18px rgba(47,99,217,0.28);">{{ auth()->user()->initials() }}</div>
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
            <a href="{{ route('logs.index') }}" class="btn btn-ghost btn-sm">Incidents & logs</a>
            <span class="text-muted text-sm">{{ now()->format('d/m/Y H:i') }}</span>
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
