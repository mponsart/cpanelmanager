<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') — Groupe Speed Cloud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded:opsz,wght,FILL@20..24,400,0..1" rel="stylesheet">
    <style>
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Light.ttf') format('truetype'); font-weight:300; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Regular.ttf') format('truetype'); font-weight:400; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-SemiBold.ttf') format('truetype'); font-weight:600; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Bold.ttf') format('truetype'); font-weight:700; }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ════════════════════════════════════════════════════
           M3 Design Tokens — Purple primary (#7c3aed)
           ════════════════════════════════════════════════════ */
        :root {
            /* Primary */
            --md-primary:            #7c3aed;
            --md-on-primary:         #ffffff;
            --md-primary-container:  #ede9fe;
            --md-on-primary-container: #4c1d95;

            /* Secondary */
            --md-secondary:          #625b71;
            --md-on-secondary:       #ffffff;
            --md-secondary-container:#e8def8;
            --md-on-secondary-container: #1d192b;

            /* Tertiary */
            --md-tertiary:           #7d5260;
            --md-on-tertiary:        #ffffff;
            --md-tertiary-container: #ffd8e4;

            /* Error */
            --md-error:              #b3261e;
            --md-on-error:           #ffffff;
            --md-error-container:    #f9dedc;
            --md-on-error-container: #410e0b;

            /* Surface (M3 neutral tones) */
            --md-surface:            #fffbfe;
            --md-surface-dim:        #ded8e1;
            --md-surface-bright:     #fffbfe;
            --md-surface-container-lowest:  #ffffff;
            --md-surface-container-low:     #f7f2fa;
            --md-surface-container:         #f3edf7;
            --md-surface-container-high:    #ece6f0;
            --md-surface-container-highest: #e6e0e9;
            --md-on-surface:         #1c1b1f;
            --md-on-surface-variant:  #49454f;
            --md-outline:            #79747e;
            --md-outline-variant:    #cac4d0;
            --md-inverse-surface:    #313033;
            --md-inverse-on-surface: #f4eff4;

            /* Semantic aliases (backward compat) */
            --bg:                    #faf8fd;
            --panel:                 var(--md-surface-container-lowest);
            --panel-solid:           #ffffff;
            --panel-soft:            var(--md-surface-container-low);
            --panel-alt:             var(--md-primary-container);
            --border:                var(--md-outline-variant);
            --border-strong:         var(--md-outline);
            --text:                  var(--md-on-surface);
            --text-muted:            var(--md-on-surface-variant);
            --accent:                var(--md-primary);
            --accent-strong:         #6d28d9;
            --accent-soft:           rgba(124,58,237,0.08);
            --danger:                var(--md-error);
            --warning:               #e65100;
            --success:               #1b5e20;
            --radius:                12px;
            --radius-sm:             8px;
            --radius-lg:             16px;
            --radius-xl:             28px;

            /* Sidebar M3 */
            --sidebar-w:             260px;

            /* M3 Elevation */
            --elevation-0:           none;
            --elevation-1:           0 1px 2px rgba(0,0,0,0.10), 0 1px 3px 1px rgba(0,0,0,0.08);
            --elevation-2:           0 1px 2px rgba(0,0,0,0.10), 0 2px 6px 2px rgba(0,0,0,0.08);
            --elevation-3:           0 4px 8px 3px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.10);

            /* Compat aliases */
            --shadow-sm:             var(--elevation-1);
            --shadow-md:             var(--elevation-2);
            --glow-accent:           0 4px 20px rgba(124,58,237,0.14);
        }

        body {
            font-family: 'Inter', 'TitilliumWeb', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            color: var(--text);
            min-height: 100vh;
            line-height: 1.5;
            background: var(--bg);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .material-symbols-rounded {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'opsz' 20;
            font-size: 20px;
            vertical-align: middle;
        }

        .app-shell { display: flex; min-height: 100vh; }

        /* ════════════════════════════════════════════════════
           M3 NAVIGATION DRAWER
           ════════════════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--md-surface-container-low);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            overflow: hidden;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.2, 0, 0, 1);
            border-right: none;
            border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
        }

        .sidebar-brand {
            padding: 16px 16px 12px 20px;
            display: flex;
            align-items: center;
            gap: 0;
            min-height: 56px;
            position: relative;
            flex-shrink: 0;
        }

        .sidebar-brand .sidebar-close {
            position: absolute;
            top: 14px;
            right: 14px;
            color: var(--md-on-surface-variant);
        }

        .sidebar-brand img {
            width: 130px;
            height: auto;
            display: block;
        }

        .sidebar-brand span { display: none; }

        .sidebar-nav {
            padding: 8px 12px;
            flex: 1;
            min-height: 0;
            overflow-y: auto;
        }

        .sidebar-nav::-webkit-scrollbar { width: 0; }

        /* M3 Section Label */
        .nav-section {
            margin: 20px 16px 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--md-on-surface-variant);
        }

        .nav-section:first-child { margin-top: 4px; }

        /* M3 Navigation Item */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 16px;
            height: 56px;
            border-radius: var(--radius-xl);
            color: var(--md-on-surface-variant);
            text-decoration: none;
            border: none;
            transition: all 0.2s cubic-bezier(0.2, 0, 0, 1);
            font-weight: 500;
            font-size: 14px;
            position: relative;
            margin-bottom: 2px;
        }

        .nav-link .icon {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: inherit;
            flex-shrink: 0;
            border: none;
            background: none;
            border-radius: 0;
        }

        .nav-link:hover {
            color: var(--md-on-surface);
            background: var(--md-surface-container-highest);
        }

        .nav-link:hover .icon { color: var(--md-on-surface); }

        .nav-link.active {
            color: var(--md-on-primary-container);
            background: var(--md-primary-container);
            font-weight: 600;
        }

        .nav-link.active::before { display: none; }

        .nav-link.active .icon {
            color: var(--md-on-primary-container);
            background: none;
            border: none;
            box-shadow: none;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            margin: 0;
            border-top: 1px solid var(--md-outline-variant);
            background: transparent;
            border-radius: 0;
            padding: 12px 16px;
            font-size: 12px;
            color: var(--md-on-surface-variant);
            flex-shrink: 0;
        }

        .sidebar-footer .user-name {
            font-weight: 600;
            color: var(--md-on-surface);
            margin-bottom: 0;
            display: block;
            font-size: 13px;
        }

        /* ════════════════════════════════════════════════════
           MAIN CONTENT
           ════════════════════════════════════════════════════ */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .menu-toggle, .sidebar-close {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: 20px;
            border: none;
            background: transparent;
            color: var(--md-on-surface-variant);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }

        .menu-toggle:hover, .sidebar-close:hover {
            background: var(--md-surface-container-highest);
            color: var(--md-on-surface);
        }

        /* M3 Top App Bar / Content Header */
        .content-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            gap: 12px;
            min-height: 64px;
        }

        .content-header h1 {
            font-size: 22px;
            font-weight: 400;
            color: var(--md-on-surface);
            letter-spacing: 0;
            line-height: 28px;
        }

        .content-header-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .content {
            padding: 0 24px 32px;
            flex: 1;
            max-width: 1400px;
        }

        /* ════════════════════════════════════════════════════
           M3 ALERTS (Banner style)
           ════════════════════════════════════════════════════ */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius);
            margin-bottom: 16px;
            font-size: 14px;
            border: none;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .alert::before { font-size: 18px; flex-shrink: 0; font-family: 'Material Symbols Rounded'; font-variation-settings: 'FILL' 1; }
        .alert-success { background: #e8f5e9; color: #1b5e20; }
        .alert-success::before { content: '\e86c'; }
        .alert-error   { background: var(--md-error-container); color: var(--md-on-error-container); }
        .alert-error::before { content: '\e000'; }
        .alert-warning { background: #fff3e0; color: #e65100; }
        .alert-warning::before { content: '\e002'; }

        /* ════════════════════════════════════════════════════
           M3 CARDS (Filled)
           ════════════════════════════════════════════════════ */
        .card {
            background: var(--md-surface-container-lowest);
            border: 1px solid var(--md-outline-variant);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 16px;
            box-shadow: var(--elevation-1);
            transition: box-shadow 0.2s cubic-bezier(0.2, 0, 0, 1);
        }

        .card:hover { box-shadow: var(--elevation-2); }

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
            letter-spacing: 0.15px;
        }

        /* ════════════════════════════════════════════════════
           M3 TABLES
           ════════════════════════════════════════════════════ */
        .table-wrap {
            overflow-x: auto;
            border-radius: var(--radius);
            border: 1px solid var(--md-outline-variant);
            background: var(--md-surface-container-lowest);
        }

        table { width: 100%; border-collapse: collapse; min-width: 620px; }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid var(--md-outline-variant);
            vertical-align: middle;
        }

        th {
            font-size: 12px;
            font-weight: 600;
            text-transform: none;
            letter-spacing: 0.1px;
            color: var(--md-on-surface-variant);
            background: var(--md-surface-container-low);
            border-bottom: 1px solid var(--md-outline-variant);
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background 0.15s ease; }
        tbody tr:hover td { background: rgba(124,58,237,0.04); }

        /* ════════════════════════════════════════════════════
           M3 FORMS
           ════════════════════════════════════════════════════ */
        .form-group { margin-bottom: 16px; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: var(--md-on-surface-variant);
            margin-bottom: 6px;
            letter-spacing: 0.4px;
        }

        /* M3 Outlined Text Field */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="date"],
        input[type="search"],
        select,
        textarea {
            width: 100%;
            background: transparent;
            border: 1px solid var(--md-outline);
            border-radius: var(--radius-sm);
            color: var(--md-on-surface);
            padding: 12px 16px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
            letter-spacing: 0.25px;
        }

        input::placeholder, textarea::placeholder { color: var(--md-outline); }

        input:focus, select:focus, textarea:focus {
            border-color: var(--md-primary);
            border-width: 2px;
            padding: 11px 15px;
            box-shadow: none;
            background: transparent;
        }

        select option { background: var(--md-surface-container-lowest); color: var(--md-on-surface); }

        .form-error { color: var(--md-error); font-size: 12px; margin-top: 4px; letter-spacing: 0.4px; }
        .form-row { display: grid; gap: 16px; }
        .form-row-2 { grid-template-columns: 1fr 1fr; }
        .form-row-3 { grid-template-columns: 1fr 1fr 1fr; }
        .cron-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); }

        /* ════════════════════════════════════════════════════
           M3 BUTTONS
           ════════════════════════════════════════════════════ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.2, 0, 0, 1);
            white-space: nowrap;
            font-family: inherit;
            letter-spacing: 0.1px;
            position: relative;
            overflow: hidden;
        }

        /* M3 Filled Button */
        .btn-primary {
            background: var(--md-primary);
            color: var(--md-on-primary);
            box-shadow: var(--elevation-1);
        }

        .btn-primary:hover {
            box-shadow: var(--elevation-2);
            filter: brightness(1.08);
        }

        /* M3 Tonal Buttons */
        .btn-danger {
            background: var(--md-error-container);
            color: var(--md-on-error-container);
        }
        .btn-danger:hover { filter: brightness(0.96); }

        .btn-success {
            background: #e8f5e9;
            color: #1b5e20;
        }
        .btn-success:hover { filter: brightness(0.96); }

        .btn-warning {
            background: #fff3e0;
            color: #e65100;
        }
        .btn-warning:hover { filter: brightness(0.96); }

        /* M3 Outlined Button */
        .btn-ghost {
            background: transparent;
            color: var(--md-primary);
            border: 1px solid var(--md-outline);
        }

        .btn-ghost:hover {
            background: var(--md-primary-container);
            border-color: var(--md-primary);
        }

        .btn-sm { padding: 6px 16px; font-size: 12px; border-radius: 16px; }

        /* ════════════════════════════════════════════════════
           M3 BADGES (Chips)
           ════════════════════════════════════════════════════ */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            border: none;
            letter-spacing: 0.1px;
        }

        .badge-success { background: #e8f5e9; color: #1b5e20; }
        .badge-error   { background: var(--md-error-container); color: var(--md-on-error-container); }
        .badge-warning { background: #fff3e0; color: #e65100; }
        .badge-muted   { background: var(--md-surface-container-high); color: var(--md-on-surface-variant); }
        .badge-accent  { background: var(--md-primary-container); color: var(--md-on-primary-container); }

        /* ════════════════════════════════════════════════════
           M3 STATS
           ════════════════════════════════════════════════════ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--md-surface-container-lowest);
            border: 1px solid var(--md-outline-variant);
            border-radius: var(--radius);
            padding: 20px 24px;
            box-shadow: var(--elevation-1);
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.2s;
        }

        .stat-card:hover { box-shadow: var(--elevation-2); }

        .stat-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 0 4px 4px 0;
            background: var(--md-primary);
        }

        .stat-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--md-on-surface-variant);
            text-transform: none;
            letter-spacing: 0.5px;
            padding-left: 16px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 600;
            margin-top: 4px;
            line-height: 1.2;
            padding-left: 16px;
            color: var(--md-on-surface);
        }

        /* ════════════════════════════════════════════════════
           M3 PAGINATION
           ════════════════════════════════════════════════════ */
        .pagination {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: 8px 14px;
            border-radius: 20px;
            border: none;
            color: var(--md-on-surface-variant);
            background: transparent;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .pagination .active {
            background: var(--md-primary-container);
            color: var(--md-on-primary-container);
        }

        .pagination a:hover {
            background: var(--md-surface-container-highest);
            color: var(--md-on-surface);
        }

        .pagination .disabled { opacity: 0.38; cursor: default; }

        /* ════════════════════════════════════════════════════
           PAGE HEADER
           ════════════════════════════════════════════════════ */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .page-header h1 {
            font-size: 24px;
            font-weight: 400;
            letter-spacing: 0;
            color: var(--md-on-surface);
        }

        /* ════════════════════════════════════════════════════
           UTILITIES
           ════════════════════════════════════════════════════ */
        .text-muted { color: var(--md-on-surface-variant); }
        .text-sm { font-size: 12px; }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 16px; }
        .mb-3 { margin-bottom: 16px; }
        .flex { display: flex; }
        .gap-2 { gap: 8px; }
        .items-center { align-items: center; }

        .table-empty { text-align: center; padding: 32px; color: var(--md-on-surface-variant); font-size: 14px; }
        .col-span-2 { grid-column: span 2; }
        .fw-medium { font-weight: 500; }
        .tabular-right { text-align: right; font-variant-numeric: tabular-nums; }
        .row-trashed { opacity: 0.38; }

        /* ════════════════════════════════════════════════════
           PROGRESS BAR
           ════════════════════════════════════════════════════ */
        .progress-track { width: 100%; height: 4px; background: var(--md-surface-container-highest); border-radius: 2px; overflow: hidden; }
        .progress-bar { height: 100%; border-radius: 2px; transition: width 0.3s ease; }
        .progress-bar-success { background: #4caf50; }
        .progress-bar-warning { background: #ff9800; }
        .progress-bar-danger  { background: var(--md-error); }

        /* ════════════════════════════════════════════════════
           KPI CARDS (Dashboard)
           ════════════════════════════════════════════════════ */
        .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .kpi-card {
            background: var(--md-surface-container-lowest);
            border: 1px solid var(--md-outline-variant);
            border-radius: var(--radius);
            padding: 20px 24px;
            box-shadow: var(--elevation-1);
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: box-shadow 0.2s cubic-bezier(0.2, 0, 0, 1);
        }

        .kpi-card:hover { box-shadow: var(--elevation-2); }

        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .kpi-icon-blue  { background: var(--md-primary-container); color: var(--md-on-primary-container); }
        .kpi-icon-green { background: #e8f5e9; color: #1b5e20; }
        .kpi-icon-red   { background: var(--md-error-container); color: var(--md-on-error-container); }
        .kpi-icon-amber { background: #fff3e0; color: #e65100; }

        .kpi-body { flex: 1; min-width: 0; }
        .kpi-label { font-size: 12px; font-weight: 500; color: var(--md-on-surface-variant); letter-spacing: 0.5px; }
        .kpi-value { font-size: 28px; font-weight: 600; color: var(--md-on-surface); line-height: 1.2; margin-top: 4px; }

        /* ════════════════════════════════════════════════════
           SHORTCUTS (Dashboard)
           ════════════════════════════════════════════════════ */
        .shortcuts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .shortcut-card {
            background: var(--md-surface-container-lowest);
            border: 1px solid var(--md-outline-variant);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--elevation-1);
            transition: box-shadow 0.2s, border-color 0.2s;
        }

        .shortcut-card:hover {
            border-color: var(--md-primary);
            box-shadow: var(--elevation-2);
        }

        .shortcut-header { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
        .shortcut-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius);
            background: var(--md-primary-container);
            color: var(--md-on-primary-container);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .shortcut-title { font-size: 14px; font-weight: 600; color: var(--md-on-surface); }
        .shortcut-desc { font-size: 12px; color: var(--md-on-surface-variant); margin-bottom: 14px; line-height: 1.5; }
        .shortcut-links { display: flex; flex-wrap: wrap; gap: 8px; }
        .shortcut-links .btn-ghost { font-size: 12px; padding: 4px 12px; }

        /* ════════════════════════════════════════════════════
           ACTIVITY (Dashboard)
           ════════════════════════════════════════════════════ */
        .activity-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .activity-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--md-outline-variant); }
        .activity-header .title { font-size: 16px; font-weight: 500; }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
        .status-dot-error   { background: var(--md-error); }
        .status-dot-warning { background: #ff9800; }
        .status-dot-success { background: #4caf50; }

        /* ════════════════════════════════════════════════════
           SEARCH BAR
           ════════════════════════════════════════════════════ */
        .dash-search-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
        .dash-search { position: relative; min-width: 260px; }
        .dash-search svg { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--md-on-surface-variant); pointer-events: none; }
        .dash-search input { width: 100%; padding: 12px 16px 12px 44px; border: none; border-radius: var(--radius-xl); background: var(--md-surface-container-high); font-size: 14px; color: var(--md-on-surface); outline: none; transition: all 0.2s; font-family: inherit; }
        .dash-search input::placeholder { color: var(--md-on-surface-variant); }
        .dash-search input:focus { background: var(--md-surface-container-highest); box-shadow: 0 0 0 2px var(--md-primary); }

        /* ════════════════════════════════════════════════════
           CODE CHIP
           ════════════════════════════════════════════════════ */
        .code {
            font-family: 'Roboto Mono', ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 12px;
            background: var(--md-surface-container-high);
            color: var(--md-on-surface);
            padding: 2px 8px;
            border-radius: 4px;
            border: none;
        }

        /* ════════════════════════════════════════════════════
           INPUT GROUP
           ════════════════════════════════════════════════════ */
        .input-group { display: flex; }

        .input-group input {
            border-radius: var(--radius-sm) 0 0 var(--radius-sm);
            border-right: none;
            flex: 1;
            min-width: 0;
        }

        .input-addon {
            background: var(--md-surface-container-high);
            border: 1px solid var(--md-outline);
            border-left: none;
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
            padding: 12px 16px;
            color: var(--md-on-surface-variant);
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            display: flex;
            align-items: center;
            user-select: none;
        }

        .pw-wrap { position: relative; }
        .pw-wrap input { padding-right: 42px; }

        .pw-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--md-on-surface-variant);
            padding: 4px;
            display: flex;
            align-items: center;
            border-radius: 20px;
            transition: color 0.2s;
        }

        .pw-toggle:hover { color: var(--md-primary); }

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
            color: var(--md-primary);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 14px;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--accent-strong); }

        /* Focus visible (M3 accessibility) */
        :focus-visible {
            outline: 2px solid var(--md-primary);
            outline-offset: 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; scroll-behavior: auto !important; }
        }

        /* ════════════════════════════════════════════════════
           OVERLAY
           ════════════════════════════════════════════════════ */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.32);
            z-index: 90;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.2, 0, 0, 1);
        }

        body.nav-open .sidebar-overlay { opacity: 1; visibility: visible; }

        /* ════════════════════════════════════════════════════
           RESPONSIVE
           ════════════════════════════════════════════════════ */
        @media (max-width: 1200px) {
            .cron-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .content-header { padding: 16px 20px; }
        }

        @media (max-width: 1100px) {
            .content { padding: 0 20px 24px; }
        }

        @media (max-width: 1024px) {
            .menu-toggle, .sidebar-close { display: inline-flex; }

            .sidebar {
                transform: translateX(-100%);
                box-shadow: var(--elevation-3);
                width: min(88vw, 320px);
                border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
            }

            body.nav-open .sidebar { transform: translateX(0); }
            .main { margin-left: 0; }
            .content-header .menu-toggle { display: inline-flex; }
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
                border-radius: var(--radius-sm);
                border-right: 1px solid var(--md-outline);
                margin-bottom: 8px;
            }

            .input-addon {
                border: 1px solid var(--md-outline);
                border-radius: var(--radius-sm);
            }

            .disk-usage-row, .domain-summary { flex-direction: column; align-items: flex-start; }
            .disk-usage-bar-wrap { max-width: none; width: 100%; }
            .inline-form .form-group { min-width: 100%; }
            .btn { width: 100%; }
            .table-wrap .btn { width: auto; }
            .pagination { gap: 2px; }
            .pagination a, .pagination span { padding: 6px 12px; }
        }

        @media (max-width: 640px) {
            .content { padding: 0 16px 20px; }
            .content-header { padding: 12px 16px; }
            .content-header h1 { font-size: 20px; }
            .card { padding: 16px; }
            th, td { padding: 10px 12px; }
            .stat-value { font-size: 22px; }
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
        <img src="/images/logo.svg" alt="Groupe Speed Cloud">
        <span>Panel d'administration</span>
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
            <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg></span> Dashboard
        </a>
        <a href="{{ route('logs.index') }}" class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
            <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="15" y2="18"/></svg></span> Journaux
        </a>
        @if($navCan('view_stats'))
        <a href="{{ route('stats.index') }}" class="nav-link {{ request()->routeIs('stats.*') ? 'active' : '' }}">
            <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="2,18 8,10 14,14 20,4"/><line x1="2" y1="22" x2="22" y2="22"/></svg></span> Statistiques
        </a>
        @endif

        @if($hasCpanelAccess)
        <div class="nav-section">cPanel</div>
        @if($navCan('view_email'))
        <a href="{{ route('email.index') }}" class="nav-link {{ request()->routeIs('email.*') ? 'active' : '' }}">
            <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,6 12,13 22,6"/></svg></span> E-mails
        </a>
        @endif
        @if($navCan('view_db'))
        <a href="{{ route('database.index') }}" class="nav-link {{ request()->routeIs('database.*') ? 'active' : '' }}">
            <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v5c0 1.66 4 3 9 3s9-1.34 9-3V5"/><path d="M3 10v5c0 1.66 4 3 9 3s9-1.34 9-3v-5"/><path d="M3 15v4c0 1.66 4 3 9 3s9-1.34 9-3v-4"/></svg></span> Bases de données
        </a>
        @endif
        @if($navCan('view_domain'))
        <a href="{{ route('domain.index') }}" class="nav-link {{ request()->routeIs('domain.*') ? 'active' : '' }}">
            <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><ellipse cx="12" cy="12" rx="4" ry="10"/><line x1="2" y1="8" x2="22" y2="8"/><line x1="2" y1="16" x2="22" y2="16"/></svg></span> Domaines
        </a>
        @endif
        @if($navCan('view_ftp'))
        <a href="{{ route('ftp.index') }}" class="nav-link {{ request()->routeIs('ftp.*') ? 'active' : '' }}">
            <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="8" rx="1.5"/><rect x="2" y="13" width="20" height="8" rx="1.5"/><circle cx="18" cy="7" r="1" fill="currentColor" stroke="none"/><circle cx="18" cy="17" r="1" fill="currentColor" stroke="none"/></svg></span> FTP
        </a>
        @endif
        @if($navCan('manage_cron'))
        <a href="{{ route('cron.index') }}" class="nav-link {{ request()->routeIs('cron.*') ? 'active' : '' }}">
            <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg></span> Cron Jobs
        </a>
        @endif
        @if($navCan('view_associations'))
        <a href="{{ route('association.index') }}" class="nav-link {{ request()->routeIs('association.*') ? 'active' : '' }}">
            <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 5a2 2 0 012-2h4l2 3h8a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/></svg></span> Associations
        </a>
        @endif
        @if($navCan('access_cpanel'))
        <a href="{{ route('cpanel.index') }}" class="nav-link {{ request()->routeIs('cpanel.index') ? 'active' : '' }}">
            <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-4"/><polyline points="13,2 22,2 22,11"/><line x1="22" y1="2" x2="10" y2="14"/></svg></span> Accès cPanel
        </a>
        <a href="{{ route('cpanel.logs') }}" class="nav-link {{ request()->routeIs('cpanel.logs') ? 'active' : '' }}">
            <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 3v18h18"/><path d="M7 15l4-6 4 3 4-7"/></svg></span> Journaux cPanel
        </a>
        @endif
        @endif

        @if($hasAdminAccess)
        <div class="nav-section">Administration</div>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4.5"/><path d="M2 21c0-4.5 4.5-8 10-8s10 3.5 10 8"/></svg></span> Utilisateurs
        </a>
        <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
            <span class="icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2L4 6v6c0 5 3.5 9 8 11 4.5-2 8-6 8-11V6l-8-4z"/></svg></span> Permissions
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:36px;height:36px;border-radius:50%;background:var(--md-primary-container);color:var(--md-on-primary-container);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;flex-shrink:0;">{{ auth()->user()->initials() }}</div>
            <div style="min-width:0;flex:1;">
                <span class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="flex-shrink:0;">
                @csrf
                <button type="submit" title="Déconnexion" style="width:40px;height:40px;color:var(--md-on-surface-variant);border:none;background:none;cursor:pointer;display:flex;align-items:center;justify-content:center;border-radius:20px;transition:background 0.2s;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<div class="main">
    <div class="content-header">
        <div style="display:flex;align-items:center;gap:12px;">
            <button type="button" class="menu-toggle" data-sidebar-open aria-label="Ouvrir le menu">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
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