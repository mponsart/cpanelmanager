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
            --bg:                #0b0b14;
            --panel:             #14141f;
            --panel-soft:        #1a1a28;
            --panel-alt:         #1e1e30;
            --border:            rgba(168,85,247,0.10);
            --border-strong:     rgba(168,85,247,0.18);
            --text:              #e8eaf0;
            --text-muted:        #6b7280;
            --accent:            #a855f7;
            --accent-strong:     #9333ea;
            --accent-soft:       rgba(168,85,247,0.14);
            --danger:            #f87171;
            --warning:           #fbbf24;
            --success:           #34d399;
            --radius:            12px;
            --sidebar-w:         256px;
            --sidebar-bg:        #0b0b14;
            --sidebar-border:    rgba(255,255,255,0.05);
            --sidebar-text:      #6b7280;
            --sidebar-text-hover:#c4c9d6;
            --sidebar-active-bg: rgba(168,85,247,0.14);
            --sidebar-active-border: rgba(168,85,247,0.40);
            --shadow-sm:         0 1px 4px rgba(0,0,0,0.45), 0 1px 2px rgba(0,0,0,0.30);
            --shadow-md:         0 6px 24px rgba(0,0,0,0.55);
            --glow-accent:       0 0 20px rgba(168,85,247,0.18);
        }

        body {
            font-family: 'TitilliumWeb', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            color: var(--text);
            min-height: 100vh;
            line-height: 1.55;
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
            padding: 22px 18px 18px;
            border-bottom: 1px solid var(--sidebar-border);
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 68px;
        }

        .sidebar-brand .sidebar-close { position: absolute; top: 12px; right: 12px; }
        .sidebar-brand img { width: 128px; height: auto; display: block; filter: brightness(0) invert(1) opacity(0.9); }
        .sidebar-brand span {
            color: var(--sidebar-text);
            font-weight: 500;
            font-size: 11px;
            display: block;
            margin-top: 2px;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .sidebar-nav { padding: 12px 10px; flex: 1; }

        .nav-section {
            margin: 20px 6px 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(107,114,128,0.55);
        }

        .nav-section:first-child { margin-top: 6px; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 11px;
            margin-bottom: 2px;
            border-radius: 10px;
            color: var(--sidebar-text);
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.14s ease;
            font-weight: 500;
            font-size: 13.5px;
        }

        .nav-link .icon {
            width: 24px;
            height: 24px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #4b5563;
            flex-shrink: 0;
            transition: inherit;
        }

        .nav-link:hover {
            color: var(--sidebar-text-hover);
            background: rgba(255,255,255,0.04);
        }

        .nav-link:hover .icon { color: #9ca3af; }

        .nav-link.active {
            color: #ddd6fe;
            background: var(--sidebar-active-bg);
            border-color: var(--sidebar-active-border);
        }

        .nav-link.active .icon { color: #c4b5fd; }

        .sidebar-footer {
            margin: 10px;
            border: 1px solid var(--sidebar-border);
            background: rgba(255,255,255,0.03);
            border-radius: 12px;
            padding: 13px;
            font-size: 12px;
            color: var(--sidebar-text);
        }

        .sidebar-footer .user-name { font-weight: 600; color: #d1d5db; margin-bottom: 2px; display: block; font-size: 13px; }

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
            background: rgba(11,11,20,0.80);
            border-bottom: 1px solid var(--sidebar-border);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 0 26px;
            height: 58px;
            display: grid;
            grid-template-columns: auto minmax(200px, 380px) auto;
            align-items: center;
            gap: 14px;
        }

        .topbar-left { display: flex; align-items: center; gap: 10px; }

        .menu-toggle, .sidebar-close {
            display: none;
            width: 36px;
            height: 36px;
            border-radius: 9px;
            border: 1px solid var(--border-strong);
            background: var(--panel);
            color: var(--text-muted);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.14s;
        }

        .menu-toggle:hover, .sidebar-close:hover {
            background: var(--panel-soft);
            border-color: var(--accent);
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
            left: 12px;
            color: #4b5563;
            pointer-events: none;
        }

        .topbar-search input {
            width: 100%;
            background: var(--panel-soft);
            border: 1px solid var(--border);
            border-radius: 9px;
            color: var(--text);
            padding: 8px 12px 8px 36px;
            font-size: 13px;
            outline: none;
            transition: all 0.14s;
            font-family: inherit;
        }

        .topbar-search input::placeholder { color: #4b5563; }

        .topbar-search input:focus {
            background: var(--panel-alt);
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-soft);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .content { padding: 26px; flex: 1; }

        /* ── ALERTS ── */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 13.5px;
            border: 1px solid;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .alert-success { background: rgba(52,211,153,0.08); border-color: rgba(52,211,153,0.25); color: #6ee7b7; }
        .alert-error   { background: rgba(248,113,113,0.08); border-color: rgba(248,113,113,0.25); color: #fca5a5; }
        .alert-warning { background: rgba(251,191,36,0.08); border-color: rgba(251,191,36,0.25); color: #fcd34d; }

        /* ── CARDS ── */
        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 22px;
            margin-bottom: 18px;
            box-shadow: var(--shadow-sm);
            transition: border-color 0.14s, box-shadow 0.14s;
        }

        .card:hover { border-color: var(--border-strong); }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 18px;
            padding-bottom: 14px;
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
            padding: 11px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        th {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: var(--text-muted);
            background: var(--panel-soft);
        }

        tbody tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(168,85,247,0.04); }

        /* ── FORMS ── */
        .form-group { margin-bottom: 16px; }

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
            background: var(--panel-soft);
            border: 1px solid var(--border-strong);
            border-radius: 9px;
            color: var(--text);
            padding: 10px 13px;
            font-size: 13.5px;
            outline: none;
            transition: all 0.14s;
            font-family: inherit;
        }

        input::placeholder, textarea::placeholder { color: #4b5563; }

        input:focus, select:focus, textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-soft);
            background: var(--panel-alt);
        }

        select option { background: var(--panel); color: var(--text); }

        .form-error { color: var(--danger); font-size: 12px; margin-top: 5px; }
        .form-row { display: grid; gap: 16px; }
        .form-row-2 { grid-template-columns: 1fr 1fr; }
        .form-row-3 { grid-template-columns: 1fr 1fr 1fr; }
        .cron-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.14s;
            white-space: nowrap;
            font-family: inherit;
            letter-spacing: 0.1px;
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
            box-shadow: 0 4px 16px rgba(168,85,247,0.35);
        }

        .btn-danger  { background: rgba(248,113,113,0.15); color: var(--danger); border-color: rgba(248,113,113,0.30); }
        .btn-danger:hover { background: rgba(248,113,113,0.25); border-color: var(--danger); }
        .btn-success { background: rgba(52,211,153,0.15); color: var(--success); border-color: rgba(52,211,153,0.30); }
        .btn-success:hover { background: rgba(52,211,153,0.25); border-color: var(--success); }
        .btn-warning { background: rgba(251,191,36,0.15); color: var(--warning); border-color: rgba(251,191,36,0.30); }
        .btn-warning:hover { background: rgba(251,191,36,0.25); border-color: var(--warning); }

        .btn-ghost {
            background: transparent;
            color: var(--text-muted);
            border-color: var(--border-strong);
        }

        .btn-ghost:hover {
            background: var(--panel-soft);
            color: var(--text);
            border-color: var(--accent);
        }

        .btn-sm { padding: 5px 12px; font-size: 12px; border-radius: 7px; }

        /* ── BADGES ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid transparent;
            letter-spacing: 0.2px;
        }

        .badge-success { background: rgba(52,211,153,0.12); color: #6ee7b7; border-color: rgba(52,211,153,0.25); }
        .badge-error   { background: rgba(248,113,113,0.12); color: #fca5a5; border-color: rgba(248,113,113,0.25); }
        .badge-warning { background: rgba(251,191,36,0.12); color: #fcd34d; border-color: rgba(251,191,36,0.25); }
        .badge-muted   { background: var(--panel-soft); color: var(--text-muted); border-color: var(--border); }
        .badge-accent  { background: var(--accent-soft); color: #ddd6fe; border-color: rgba(168,85,247,0.30); }

        /* ── STATS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }

        .stat-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 22px;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
            transition: border-color 0.14s, box-shadow 0.14s;
        }

        .stat-card:hover { border-color: var(--border-strong); box-shadow: var(--glow-accent); }

        .stat-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 14px;
            bottom: 14px;
            width: 3px;
            border-radius: 0 2px 2px 0;
            background: linear-gradient(180deg, var(--accent), var(--accent-strong));
        }

        .stat-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.7px;
            padding-left: 13px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-top: 6px;
            line-height: 1.15;
            padding-left: 13px;
            color: var(--text);
        }

        /* ── PAGINATION ── */
        .pagination {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid var(--border);
            color: var(--text-muted);
            background: var(--panel);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.12s;
        }

        .pagination .active { background: var(--accent); color: #fff; border-color: var(--accent); }
        .pagination a:hover { background: var(--panel-soft); color: var(--accent); border-color: var(--accent); }
        .pagination .disabled { opacity: 0.35; cursor: default; }

        /* ── PAGE HEADER ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
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

        /* ── UTILITIES ── */
        .table-empty { text-align: center; padding: 28px; color: var(--text-muted); font-size: .875rem; }
        .col-span-2 { grid-column: span 2; }
        .fw-medium { font-weight: 500; }
        .tabular-right { text-align: right; font-variant-numeric: tabular-nums; }
        .row-trashed { opacity: 0.4; }

        /* ── PROGRESS BAR ── */
        .progress-track { width: 100%; height: 6px; background: var(--panel-soft); border-radius: 999px; overflow: hidden; }
        .progress-bar { height: 100%; border-radius: 999px; transition: width 0.3s ease; }
        .progress-bar-success { background: var(--success); }
        .progress-bar-warning { background: var(--warning); }
        .progress-bar-danger { background: var(--danger); }

        /* ── KPI (Dashboard) ── */
        .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 22px; }
        .kpi-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 22px;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: border-color 0.14s, box-shadow 0.14s;
        }
        .kpi-card:hover { border-color: var(--border-strong); box-shadow: var(--glow-accent); }
        .kpi-icon { width: 42px; height: 42px; border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .kpi-icon-blue   { background: rgba(168,85,247,0.15); color: #c4b5fd; }
        .kpi-icon-green  { background: rgba(52,211,153,0.12); color: #6ee7b7; }
        .kpi-icon-red    { background: rgba(248,113,113,0.12); color: #fca5a5; }
        .kpi-icon-amber  { background: rgba(251,191,36,0.12); color: #fcd34d; }
        .kpi-body { flex: 1; min-width: 0; }
        .kpi-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.6px; }
        .kpi-value { font-size: 26px; font-weight: 700; color: var(--text); line-height: 1.2; margin-top: 4px; }

        /* ── SHORTCUTS (Dashboard) ── */
        .shortcuts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 22px; }
        .shortcut-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px;
            box-shadow: var(--shadow-sm);
            transition: border-color 0.14s, box-shadow 0.14s, transform 0.14s;
        }
        .shortcut-card:hover {
            border-color: var(--border-strong);
            box-shadow: var(--glow-accent);
            transform: translateY(-2px);
        }
        .shortcut-header { display: flex; align-items: center; gap: 11px; margin-bottom: 10px; }
        .shortcut-icon { width: 34px; height: 34px; border-radius: 9px; background: var(--accent-soft); color: #ddd6fe; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .shortcut-title { font-size: 13px; font-weight: 600; color: var(--text); }
        .shortcut-desc { font-size: 11.5px; color: var(--text-muted); margin-bottom: 12px; line-height: 1.5; }
        .shortcut-links { display: flex; flex-wrap: wrap; gap: 6px; }
        .shortcut-links .btn-ghost { font-size: 11.5px; padding: 4px 10px; }

        /* ── ACTIVITY (Dashboard) ── */
        .activity-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .activity-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid var(--border); }
        .activity-header .title { font-size: 14px; font-weight: 600; }
        .status-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
        .status-dot-error   { background: var(--danger); box-shadow: 0 0 6px rgba(248,113,113,0.6); }
        .status-dot-warning { background: var(--warning); box-shadow: 0 0 6px rgba(251,191,36,0.6); }
        .status-dot-success { background: var(--success); box-shadow: 0 0 6px rgba(52,211,153,0.6); }

        /* ── DASH SEARCH ── */
        .dash-search-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
        .dash-search { position: relative; min-width: 240px; }
        .dash-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #4b5563; pointer-events: none; }
        .dash-search input { width: 100%; padding: 9px 13px 9px 36px; border: 1px solid var(--border); border-radius: 9px; background: var(--panel); font-size: 13px; color: var(--text); outline: none; transition: all 0.14s; font-family: inherit; }
        .dash-search input::placeholder { color: #4b5563; }
        .dash-search input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); background: var(--panel-alt); }

        .code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', monospace;
            font-size: 12px;
            background: var(--panel-soft);
            color: #c4b5fd;
            padding: 2px 7px;
            border-radius: 5px;
            border: 1px solid rgba(168,85,247,0.20);
        }

        .input-group { display: flex; }

        .input-group input {
            border-radius: 9px 0 0 9px;
            border-right: none;
            flex: 1;
            min-width: 0;
        }

        .input-addon {
            background: var(--panel-soft);
            border: 1px solid var(--border-strong);
            border-left: none;
            border-radius: 0 9px 9px 0;
            padding: 10px 13px;
            color: var(--text-muted);
            font-size: 13px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            user-select: none;
        }

        .pw-wrap { position: relative; }
        .pw-wrap input { padding-right: 42px; }

        .pw-toggle {
            position: absolute;
            right: 11px;
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
            gap: 14px;
            flex-wrap: wrap;
        }

        .inline-form .form-group { flex: 1; margin-bottom: 0; min-width: 260px; }
        .disk-usage-row { display: flex; align-items: center; gap: 20px; padding: 8px 0; }
        .disk-usage-details { flex: 1; }
        .disk-usage-bar-wrap { flex: 1; max-width: 200px; }
        .domain-summary { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .back-link-wrap { margin-bottom: 20px; }

        .back-link {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            transition: color 0.12s;
        }

        .back-link:hover { color: #ddd6fe; }

        :focus-visible {
            outline: 2px solid rgba(168,85,247,0.55);
            outline-offset: 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; scroll-behavior: auto !important; }
        }

        /* ── OVERLAY ── */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.65);
            z-index: 90;
            opacity: 0;
            visibility: hidden;
            transition: all 0.22s ease;
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
            .kpi-grid { grid-template-columns: repeat(2, 1fr); }
            .shortcuts-grid { grid-template-columns: 1fr; }
            .activity-grid { grid-template-columns: 1fr; }

            .input-group { flex-wrap: wrap; }

            .input-group input {
                border-radius: 9px;
                border-right: 1px solid var(--border-strong);
                margin-bottom: 8px;
            }

            .input-addon {
                border: 1px solid var(--border-strong);
                border-radius: 9px;
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
            .pagination a, .pagination span { padding: 5px 10px; }
        }

        @media (max-width: 640px) {
            .content { padding: 14px; }
            .topbar { padding: 0 14px; }
            .topbar-title { font-size: 14px; }
            .card { padding: 16px; }
            th, td { padding: 9px 11px; }
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
            <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--accent),var(--accent-strong));color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;box-shadow:0 0 14px rgba(168,85,247,0.4);">{{ auth()->user()->initials() }}</div>
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
@stack('modals')
@stack('scripts')
</body>
</html>
