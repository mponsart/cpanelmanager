<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') — Groupe Speed Cloud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Google+Sans+Text:wght@400;500;700&family=Roboto:wght@400;500;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --md-primary: #1a73e8;
            --md-on-primary: #ffffff;
            --md-primary-container: #d3e3fd;
            --md-on-primary-container: #041e49;
            --md-secondary: #5f6368;
            --md-on-secondary: #ffffff;
            --md-secondary-container: #e8eaed;
            --md-on-secondary-container: #1f1f1f;
            --md-tertiary: #1e8e3e;
            --md-on-tertiary: #ffffff;
            --md-tertiary-container: #ceead6;
            --md-on-tertiary-container: #0d652d;
            --md-error: #d93025;
            --md-on-error: #ffffff;
            --md-error-container: #fce8e6;
            --md-on-error-container: #5f2120;
            --md-surface: #ffffff;
            --md-surface-dim: #dde3ea;
            --md-surface-bright: #f8f9fa;
            --md-surface-container-lowest: #ffffff;
            --md-surface-container-low: #f8f9fa;
            --md-surface-container: #f1f3f4;
            --md-surface-container-high: #e8eaed;
            --md-surface-container-highest: #dadce0;
            --md-on-surface: #1f1f1f;
            --md-on-surface-variant: #5f6368;
            --md-outline: #80868b;
            --md-outline-variant: #dadce0;
            --md-inverse-surface: #303134;
            --md-inverse-on-surface: #e8eaed;
            --bg: #f8f9fa;
            --panel: var(--md-surface-container-lowest);
            --panel-solid: #ffffff;
            --panel-soft: var(--md-surface-container-low);
            --panel-alt: var(--md-primary-container);
            --border: var(--md-outline-variant);
            --border-strong: var(--md-outline);
            --text: var(--md-on-surface);
            --text-muted: var(--md-on-surface-variant);
            --accent: var(--md-primary);
            --accent-strong: #1557b0;
            --accent-soft: rgba(26,115,232,0.08);
            --danger: var(--md-error);
            --warning: #e37400;
            --success: #1e8e3e;
            --radius-xs: 8px;
            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 28px;
            --sidebar-w: 280px;
            --elevation-0: none;
            --elevation-1: 0 1px 2px 0 rgba(60,64,67,0.30), 0 1px 3px 1px rgba(60,64,67,0.15);
            --elevation-2: 0 1px 2px 0 rgba(60,64,67,0.30), 0 2px 6px 2px rgba(60,64,67,0.15);
            --elevation-3: 0 1px 3px 0 rgba(60,64,67,0.30), 0 4px 8px 3px rgba(60,64,67,0.15);
            --shadow-sm: var(--elevation-1);
            --shadow-md: var(--elevation-2);
            --glow-accent: none;
        }

        body {
            font-family: 'Google Sans Text', 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            color: var(--text);
            min-height: 100vh;
            line-height: 1.5;
            background: var(--bg);
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, .card-title, .kpi-label, .nav-section, .kpi-value, .stat-value {
            font-family: 'Google Sans', 'Roboto', sans-serif;
        }

        .material-symbols-rounded {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'opsz' 24;
            font-size: 24px;
            vertical-align: middle;
        }

        a { color: var(--md-primary); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .app-shell { display: flex; min-height: 100vh; }

        /* ── SIDEBAR ───────────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--md-surface-container-low);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            overflow: hidden;
            z-index: 100;
            transition: transform 0.25s cubic-bezier(0.2, 0, 0, 1);
            border-right: 1px solid var(--md-outline-variant);
        }

        .sidebar-brand {
            padding: 20px 16px 16px 24px;
            display: flex;
            align-items: center;
            min-height: 64px;
            position: relative;
            flex-shrink: 0;
        }

        .sidebar-brand .sidebar-close {
            position: absolute;
            top: 16px;
            right: 12px;
            color: var(--md-on-surface-variant);
        }

        .sidebar-brand img { height: 26px; width: auto; display: block; }
        .sidebar-brand span { display: none; }

        .sidebar-nav {
            padding: 4px 12px;
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--md-outline-variant) transparent;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--md-outline-variant); border-radius: 4px; }

        .nav-section {
            margin: 16px 16px 4px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--md-on-surface-variant);
        }

        .nav-section:first-child { margin-top: 0; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 16px;
            height: 48px;
            border-radius: var(--radius-xl);
            color: var(--md-on-surface-variant);
            text-decoration: none;
            border: none;
            transition: all 0.15s cubic-bezier(0.2, 0, 0, 1);
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .nav-link .icon {
            width: 24px; height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: inherit;
            flex-shrink: 0;
        }

        .nav-link:hover {
            color: var(--md-on-surface);
            background: rgba(31,31,31,0.08);
            text-decoration: none;
        }

        .nav-link.active {
            color: var(--md-on-primary-container);
            background: var(--md-primary-container);
            font-weight: 600;
        }

        .nav-link.active .icon { color: var(--md-on-primary-container); }

        .sidebar-footer {
            border-top: 1px solid var(--md-outline-variant);
            padding: 12px 16px;
            flex-shrink: 0;
        }

        .sidebar-footer .user-name {
            font-weight: 500;
            color: var(--md-on-surface);
            display: block;
            font-size: 14px;
        }

        /* ── MAIN ──────────────────────────────────────────── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .menu-toggle, .sidebar-close {
            display: none;
            width: 48px; height: 48px;
            border-radius: 50%;
            border: none;
            background: transparent;
            color: var(--md-on-surface-variant);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.15s;
        }

        .menu-toggle:hover, .sidebar-close:hover { background: rgba(31,31,31,0.08); }

        .content-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            gap: 12px;
            min-height: 64px;
            border-bottom: 1px solid var(--md-outline-variant);
            background: var(--md-surface);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .content-header h1 {
            font-size: 22px;
            font-weight: 400;
            color: var(--md-on-surface);
        }

        .content-header-right { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
        .content { padding: 24px; flex: 1; max-width: 1400px; }

        /* ── ALERTS ────────────────────────────────────────── */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 16px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            line-height: 1.5;
        }

        .alert::before {
            font-size: 20px;
            flex-shrink: 0;
            font-family: 'Material Symbols Rounded';
            font-variation-settings: 'FILL' 1, 'wght' 400, 'opsz' 24;
        }

        .alert-success { background: var(--md-tertiary-container); color: var(--md-on-tertiary-container); }
        .alert-success::before { content: '\e86c'; }
        .alert-error { background: var(--md-error-container); color: var(--md-on-error-container); }
        .alert-error::before { content: '\e000'; }
        .alert-warning { background: #fef7e0; color: #3c3000; }
        .alert-warning::before { content: '\e002'; }

        /* ── CARDS ─────────────────────────────────────────── */
        .card {
            background: var(--md-surface);
            border: 1px solid var(--md-outline-variant);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 16px;
            transition: box-shadow 0.15s;
        }

        .card:hover { box-shadow: var(--elevation-1); }

        .card-title {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--md-outline-variant);
            color: var(--md-on-surface);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── TABLES ────────────────────────────────────────── */
        .table-wrap {
            overflow-x: auto;
            border-radius: var(--radius-sm);
            border: 1px solid var(--md-outline-variant);
        }

        table { width: 100%; border-collapse: collapse; min-width: 620px; }

        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--md-outline-variant);
            vertical-align: middle;
        }

        th {
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.1px;
            color: var(--md-on-surface-variant);
            background: var(--md-surface-container-low);
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background 0.1s; }
        tbody tr:hover td { background: rgba(26,115,232,0.04); }

        /* ── FORMS ─────────────────────────────────────────── */
        .form-group { margin-bottom: 16px; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: var(--md-on-surface-variant);
            margin-bottom: 6px;
            letter-spacing: 0.1px;
        }

        input[type="text"], input[type="email"], input[type="password"],
        input[type="number"], input[type="date"], input[type="search"],
        select, textarea {
            width: 100%;
            background: var(--md-surface);
            border: 1px solid var(--md-outline);
            border-radius: var(--radius-sm);
            color: var(--md-on-surface);
            padding: 10px 16px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            font-family: inherit;
        }

        input::placeholder, textarea::placeholder { color: var(--md-outline); }

        input:focus, select:focus, textarea:focus {
            border-color: var(--md-primary);
            box-shadow: 0 0 0 1px var(--md-primary);
        }

        select option { background: var(--md-surface); color: var(--md-on-surface); }
        .form-error { color: var(--md-error); font-size: 12px; margin-top: 4px; }
        .form-row { display: grid; gap: 16px; }
        .form-row-2 { grid-template-columns: 1fr 1fr; }
        .form-row-3 { grid-template-columns: 1fr 1fr 1fr; }
        .cron-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); }

        /* ── BUTTONS ───────────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 24px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s cubic-bezier(0.2, 0, 0, 1);
            white-space: nowrap;
            font-family: 'Google Sans Text', inherit;
            letter-spacing: 0.1px;
            line-height: 20px;
        }

        .btn:hover { text-decoration: none; }

        .btn-primary { background: var(--md-primary); color: var(--md-on-primary); }
        .btn-primary:hover { box-shadow: var(--elevation-1); background: #1557b0; }

        .btn-danger { background: var(--md-error-container); color: var(--md-on-error-container); }
        .btn-danger:hover { background: #f8d7da; }

        .btn-success { background: var(--md-tertiary-container); color: var(--md-on-tertiary-container); }
        .btn-success:hover { background: #b8dfc2; }

        .btn-warning { background: #fef7e0; color: #3c3000; }
        .btn-warning:hover { background: #fdefc0; }

        .btn-ghost { background: transparent; color: var(--md-primary); border: 1px solid var(--md-outline-variant); }
        .btn-ghost:hover { background: rgba(26,115,232,0.08); border-color: var(--md-primary); }

        .btn-sm { padding: 6px 16px; font-size: 12px; border-radius: 16px; }

        /* ── BADGES ────────────────────────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success { background: var(--md-tertiary-container); color: var(--md-on-tertiary-container); }
        .badge-error { background: var(--md-error-container); color: var(--md-on-error-container); }
        .badge-warning { background: #fef7e0; color: #3c3000; }
        .badge-muted { background: var(--md-surface-container-high); color: var(--md-on-surface-variant); }
        .badge-accent { background: var(--md-primary-container); color: var(--md-on-primary-container); }

        /* ── STATS ─────────────────────────────────────────── */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 20px; }

        .stat-card {
            background: var(--md-surface);
            border: 1px solid var(--md-outline-variant);
            border-radius: var(--radius);
            padding: 20px 24px;
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.15s;
        }

        .stat-card:hover { box-shadow: var(--elevation-1); }
        .stat-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; border-radius: 0 3px 3px 0; background: var(--md-primary); }
        .stat-label { font-size: 12px; font-weight: 500; color: var(--md-on-surface-variant); padding-left: 14px; }
        .stat-value { font-size: 28px; font-weight: 500; margin-top: 4px; line-height: 1.2; padding-left: 14px; color: var(--md-on-surface); }

        /* ── PAGINATION ────────────────────────────────────── */
        .pagination { display: flex; align-items: center; gap: 4px; margin-top: 20px; flex-wrap: wrap; }
        .pagination a, .pagination span { padding: 8px 14px; border-radius: 20px; color: var(--md-on-surface-variant); background: transparent; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.15s; }
        .pagination .active { background: var(--md-primary-container); color: var(--md-on-primary-container); }
        .pagination a:hover { background: rgba(31,31,31,0.08); color: var(--md-on-surface); text-decoration: none; }
        .pagination .disabled { opacity: 0.38; cursor: default; }

        /* ── PAGE HEADER ───────────────────────────────────── */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; gap: 12px; flex-wrap: wrap; }
        .page-header h1 { font-size: 24px; font-weight: 400; color: var(--md-on-surface); }

        /* ── UTILITIES ─────────────────────────────────────── */
        .text-muted { color: var(--md-on-surface-variant); }
        .text-sm { font-size: 12px; }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 16px; }
        .mb-3 { margin-bottom: 16px; }
        .mb-4 { margin-bottom: 24px; }
        .flex { display: flex; }
        .gap-2 { gap: 8px; }
        .items-center { align-items: center; }
        .table-empty { text-align: center; padding: 32px; color: var(--md-on-surface-variant); font-size: 14px; }
        .col-span-2 { grid-column: span 2; }
        .fw-medium { font-weight: 500; }
        .tabular-right { text-align: right; font-variant-numeric: tabular-nums; }
        .row-trashed { opacity: 0.38; }

        /* ── PROGRESS ──────────────────────────────────────── */
        .progress-track { width: 100%; height: 4px; background: var(--md-surface-container-highest); border-radius: 2px; overflow: hidden; }
        .progress-bar { height: 100%; border-radius: 2px; transition: width 0.3s ease; }
        .progress-bar-success { background: var(--md-tertiary); }
        .progress-bar-warning { background: var(--warning); }
        .progress-bar-danger { background: var(--md-error); }

        /* ── KPI ───────────────────────────────────────────── */
        .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .kpi-card { background: var(--md-surface); border: 1px solid var(--md-outline-variant); border-radius: var(--radius); padding: 20px; display: flex; align-items: flex-start; gap: 16px; transition: box-shadow 0.15s; }
        .kpi-card:hover { box-shadow: var(--elevation-1); }
        .kpi-icon { width: 48px; height: 48px; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .kpi-icon-blue { background: var(--md-primary-container); color: var(--md-on-primary-container); }
        .kpi-icon-green { background: var(--md-tertiary-container); color: var(--md-on-tertiary-container); }
        .kpi-icon-red { background: var(--md-error-container); color: var(--md-on-error-container); }
        .kpi-icon-amber { background: #fef7e0; color: #3c3000; }
        .kpi-body { flex: 1; min-width: 0; }
        .kpi-label { font-size: 12px; font-weight: 500; color: var(--md-on-surface-variant); }
        .kpi-value { font-size: 28px; font-weight: 500; color: var(--md-on-surface); line-height: 1.2; margin-top: 4px; }

        /* ── SHORTCUTS ─────────────────────────────────────── */
        .shortcuts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .shortcut-card { background: var(--md-surface); border: 1px solid var(--md-outline-variant); border-radius: var(--radius); padding: 20px; transition: box-shadow 0.15s, border-color 0.15s; }
        .shortcut-card:hover { border-color: var(--md-primary); box-shadow: var(--elevation-1); }
        .shortcut-header { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
        .shortcut-icon { width: 40px; height: 40px; border-radius: var(--radius); background: var(--md-primary-container); color: var(--md-on-primary-container); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .shortcut-title { font-size: 14px; font-weight: 500; color: var(--md-on-surface); }
        .shortcut-desc { font-size: 13px; color: var(--md-on-surface-variant); margin-bottom: 14px; line-height: 1.5; }
        .shortcut-links { display: flex; flex-wrap: wrap; gap: 8px; }
        .shortcut-links .btn-ghost { font-size: 12px; padding: 4px 12px; }

        /* ── ACTIVITY ──────────────────────────────────────── */
        .activity-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .activity-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--md-outline-variant); }
        .activity-header .title { font-size: 16px; font-weight: 500; }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
        .status-dot-error { background: var(--md-error); }
        .status-dot-warning { background: var(--warning); }
        .status-dot-success { background: var(--md-tertiary); }

        /* ── SEARCH ────────────────────────────────────────── */
        .dash-search-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
        .dash-search { position: relative; min-width: 280px; flex: 1; max-width: 480px; }
        .dash-search svg { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--md-on-surface-variant); pointer-events: none; }
        .dash-search input { width: 100%; padding: 12px 16px 12px 44px; border: 1px solid var(--md-outline-variant); border-radius: var(--radius-xl); background: var(--md-surface); font-size: 14px; color: var(--md-on-surface); outline: none; transition: all 0.15s; font-family: inherit; }
        .dash-search input::placeholder { color: var(--md-on-surface-variant); }
        .dash-search input:focus { border-color: var(--md-primary); box-shadow: 0 0 0 1px var(--md-primary); }

        /* ── CODE ──────────────────────────────────────────── */
        .code { font-family: 'Roboto Mono', ui-monospace, monospace; font-size: 12px; background: var(--md-surface-container); color: var(--md-on-surface); padding: 2px 8px; border-radius: 4px; }

        /* ── INPUT GROUP ───────────────────────────────────── */
        .input-group { display: flex; }
        .input-group input { border-radius: var(--radius-sm) 0 0 var(--radius-sm); border-right: none; flex: 1; min-width: 0; }
        .input-addon { background: var(--md-surface-container); border: 1px solid var(--md-outline); border-left: none; border-radius: 0 var(--radius-sm) var(--radius-sm) 0; padding: 10px 16px; color: var(--md-on-surface-variant); font-size: 14px; font-weight: 500; white-space: nowrap; display: flex; align-items: center; }
        .pw-wrap { position: relative; }
        .pw-wrap input { padding-right: 42px; }
        .pw-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--md-on-surface-variant); padding: 4px; display: flex; align-items: center; border-radius: 50%; transition: all 0.15s; }
        .pw-toggle:hover { color: var(--md-primary); background: rgba(26,115,232,0.08); }
        .inline-form { display: flex; align-items: flex-end; gap: 14px; flex-wrap: wrap; }
        .inline-form .form-group { flex: 1; margin-bottom: 0; min-width: 260px; }
        .disk-usage-row { display: flex; align-items: center; gap: 20px; padding: 8px 0; }
        .disk-usage-details { flex: 1; }
        .disk-usage-bar-wrap { flex: 1; max-width: 200px; }
        .domain-summary { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .back-link-wrap { margin-bottom: 20px; }
        .back-link { color: var(--md-primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 4px; font-size: 14px; }
        .back-link:hover { text-decoration: none; color: var(--accent-strong); }

        :focus-visible { outline: 2px solid var(--md-primary); outline-offset: 2px; }
        @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }

        /* ── OVERLAY ───────────────────────────────────────── */
        .sidebar-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.32); z-index: 90; opacity: 0; visibility: hidden; transition: all 0.25s; }
        body.nav-open .sidebar-overlay { opacity: 1; visibility: visible; }

        /* ── RESPONSIVE ────────────────────────────────────── */
        @media (max-width: 1200px) { .cron-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }

        @media (max-width: 1024px) {
            .menu-toggle, .sidebar-close { display: inline-flex; }
            .sidebar { transform: translateX(-100%); box-shadow: var(--elevation-3); width: min(85vw, 320px); border-right: none; }
            body.nav-open .sidebar { transform: translateX(0); }
            .main { margin-left: 0; }
        }

        @media (max-width: 900px) {
            .form-row, .form-row-2, .form-row-3 { grid-template-columns: 1fr !important; }
            .stats-grid { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); }
            .kpi-grid { grid-template-columns: repeat(2, 1fr); }
            .shortcuts-grid { grid-template-columns: 1fr; }
            .activity-grid { grid-template-columns: 1fr; }
            .input-group { flex-wrap: wrap; }
            .input-group input { border-radius: var(--radius-sm); border-right: 1px solid var(--md-outline); margin-bottom: 8px; }
            .input-addon { border: 1px solid var(--md-outline); border-radius: var(--radius-sm); }
            .disk-usage-row, .domain-summary { flex-direction: column; align-items: flex-start; }
            .disk-usage-bar-wrap { max-width: none; width: 100%; }
            .inline-form .form-group { min-width: 100%; }
            .btn { width: 100%; }
            .table-wrap .btn { width: auto; }
        }

        @media (max-width: 640px) {
            .content { padding: 16px; }
            .content-header { padding: 12px 16px; }
            .content-header h1 { font-size: 18px; }
            .card { padding: 16px; }
            th, td { padding: 10px 12px; }
            .stat-value { font-size: 22px; }
            .kpi-grid { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="app-shell">

<aside class="sidebar">
    <div class="sidebar-brand">
        <button type="button" class="sidebar-close" data-sidebar-close aria-label="Fermer le menu">
            <span class="material-symbols-rounded">close</span>
        </button>
        <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
    </div>

    @php
        $navUser  = auth()->user();
        $navPerms = $navUser->isSuperAdmin()
            ? null
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
            <span class="icon"><span class="material-symbols-rounded">dashboard</span></span> Dashboard
        </a>
        <a href="{{ route('logs.index') }}" class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
            <span class="icon"><span class="material-symbols-rounded">receipt_long</span></span> Journaux
        </a>
        @if($navCan('view_stats'))
        <a href="{{ route('stats.index') }}" class="nav-link {{ request()->routeIs('stats.*') ? 'active' : '' }}">
            <span class="icon"><span class="material-symbols-rounded">monitoring</span></span> Statistiques
        </a>
        @endif

        @if($hasCpanelAccess)
        <div class="nav-section">cPanel</div>
        @if($navCan('view_db'))
        <a href="{{ route('database.index') }}" class="nav-link {{ request()->routeIs('database.*') ? 'active' : '' }}">
            <span class="icon"><span class="material-symbols-rounded">database</span></span> Bases de données
        </a>
        @endif
        @if($navCan('view_domain'))
        <a href="{{ route('domain.index') }}" class="nav-link {{ request()->routeIs('domain.*') ? 'active' : '' }}">
            <span class="icon"><span class="material-symbols-rounded">language</span></span> Domaines
        </a>
        @endif
        @if($navCan('view_ftp'))
        <a href="{{ route('ftp.index') }}" class="nav-link {{ request()->routeIs('ftp.*') ? 'active' : '' }}">
            <span class="icon"><span class="material-symbols-rounded">dns</span></span> FTP
        </a>
        @endif
        @if($navCan('view_associations'))
        <a href="{{ route('association.index') }}" class="nav-link {{ request()->routeIs('association.*') ? 'active' : '' }}">
            <span class="icon"><span class="material-symbols-rounded">folder_shared</span></span> Associations
        </a>
        @endif
        @if($navCan('access_cpanel'))
        <a href="{{ route('cpanel.index') }}" class="nav-link {{ request()->routeIs('cpanel.index') ? 'active' : '' }}">
            <span class="icon"><span class="material-symbols-rounded">open_in_new</span></span> Accès cPanel
        </a>
        <a href="{{ route('cpanel.logs') }}" class="nav-link {{ request()->routeIs('cpanel.logs') ? 'active' : '' }}">
            <span class="icon"><span class="material-symbols-rounded">timeline</span></span> Journaux cPanel
        </a>
        @endif
        @endif

        @if($hasAdminAccess)
        <div class="nav-section">Administration</div>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <span class="icon"><span class="material-symbols-rounded">group</span></span> Utilisateurs
        </a>
        <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
            <span class="icon"><span class="material-symbols-rounded">shield</span></span> Permissions
        </a>
        @if($navCan('view_credentials'))
        <a href="{{ route('credentials.index') }}" class="nav-link {{ request()->routeIs('credentials.*') ? 'active' : '' }}">
            <span class="icon"><span class="material-symbols-rounded">key</span></span> Identifiants
        </a>
        @endif
        @endif
    </nav>

    <div class="sidebar-footer">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:36px;height:36px;border-radius:50%;background:var(--md-primary-container);color:var(--md-on-primary-container);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:500;flex-shrink:0;">{{ auth()->user()->initials() }}</div>
            <div style="min-width:0;flex:1;">
                <span class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</span>
                <span style="display:block;font-size:12px;color:var(--md-on-surface-variant);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->email }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="flex-shrink:0;">
                @csrf
                <button type="submit" title="Déconnexion" style="width:40px;height:40px;color:var(--md-on-surface-variant);border:none;background:none;cursor:pointer;display:flex;align-items:center;justify-content:center;border-radius:50%;transition:background 0.15s;">
                    <span class="material-symbols-rounded" style="font-size:20px;">logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<div class="main">
    <div class="content-header">
        <div style="display:flex;align-items:center;gap:8px;">
            <button type="button" class="menu-toggle" data-sidebar-open aria-label="Ouvrir le menu">
                <span class="material-symbols-rounded">menu</span>
            </button>
            <h1>@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="content-header-right">
            <span class="text-muted text-sm" style="font-variant-numeric:tabular-nums;">{{ now()->format('d/m/Y') }}</span>
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
    var EYE     = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12c2-5 6-8 11-8s9 3 11 8c-2 5-6 8-11 8S3 17 1 12z"/><circle cx="12" cy="12" r="3.5"/></svg>';
    var EYE_OFF = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12c2-5 6-8 11-8s9 3 11 8"/><path d="M12 20c-5 0-9-3-11-8"/><line x1="2" y1="2" x2="22" y2="22"/></svg>';
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
</script>
@stack('modals')
@stack('scripts')
</body>
</html>