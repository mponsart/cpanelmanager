<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Groupe Speed Cloud</title>
    <style>
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Regular.ttf') format('truetype'); font-weight:400; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-SemiBold.ttf') format('truetype'); font-weight:600; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Bold.ttf') format('truetype'); font-weight:700; }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'TitilliumWeb', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            min-height: 100vh;
            background: #f4f5f7;
            color: #1a1a2e;
        }

        .page {
            min-height: 100vh;
            display: grid;
            grid-template-rows: auto 1fr auto;
        }

        /* ── Top bar ── */
        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 32px;
        }

        .top-bar img { height: 28px; width: auto; }

        .top-bar .env {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #7c3aed;
            padding: 4px 10px;
            border-radius: 6px;
            background: rgba(124,58,237,0.08);
        }

        /* ── Centre ── */
        .center {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 24px;
        }

        .box {
            width: 100%;
            max-width: 400px;
            animation: show 0.35s ease both;
        }

        @keyframes show {
            from { opacity: 0; transform: scale(0.98) translateY(6px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        .lock-icon {
            width: 52px;
            height: 52px;
            margin: 0 auto 24px;
            border-radius: 14px;
            background: linear-gradient(135deg, #7c3aed, #6366f1);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(124,58,237,0.22);
        }

        .box h1 {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .box .sub {
            text-align: center;
            font-size: 13.5px;
            color: #6b7280;
            margin-bottom: 32px;
        }

        /* Alertes */
        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 13px;
            font-weight: 500;
        }

        .alert svg { flex-shrink: 0; }
        .alert-error   { background: #fef2f2; color: #b91c1c; }
        .alert-success { background: #ecfdf5; color: #047857; }

        /* Bouton */
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .btn-google {
            display: flex;
            align-items: center;
            gap: 14px;
            width: 100%;
            padding: 16px 20px;
            text-decoration: none;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
            background: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.12s;
        }

        .btn-google:hover { background: #f9fafb; }

        .btn-google .g-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .btn-google .arrow {
            margin-left: auto;
            color: #9ca3af;
            transition: transform 0.15s, color 0.15s;
        }

        .btn-google:hover .arrow {
            transform: translateX(3px);
            color: #7c3aed;
        }

        /* ── Notice réglementaire ── */
        .notice {
            margin-top: 24px;
            padding: 16px 18px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid #e5e7eb;
        }

        .notice-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #b45309;
            margin-bottom: 10px;
        }

        .notice-title svg { flex-shrink: 0; }

        .notice p {
            font-size: 12px;
            line-height: 1.65;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .notice p:last-child { margin-bottom: 0; }

        .notice strong { color: #374151; font-weight: 600; }

        /* ── Footer ── */
        .bottom {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            padding: 20px 32px;
            font-size: 11.5px;
            color: #9ca3af;
        }

        .bottom .dot {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: #d1d5db;
        }

        @media (max-width: 480px) {
            .top-bar { padding: 16px 20px; }
            .center { padding: 0 16px; }
            .box h1 { font-size: 21px; }
            .bottom { flex-wrap: wrap; gap: 8px 16px; padding: 16px; }
        }
    </style>
</head>
<body>

<div class="page">
    <header class="top-bar">
        <img src="/images/logo.svg" alt="Groupe Speed Cloud">
        <span class="env">Interne</span>
    </header>

    <main class="center">
        <div class="box">
            <div class="lock-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>

            @if(session('error'))
                <div class="alert alert-error">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <h1>Accès au panel</h1>
            <p class="sub">Identifiez-vous pour gérer l'infrastructure.</p>

            <div class="card">
                <a href="{{ route('auth.google') }}" class="btn-google">
                    <span class="g-logo">
                        <svg width="18" height="18" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                    </span>
                    Continuer avec Google
                    <svg class="arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="notice">
                <div class="notice-title">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Avertissement
                </div>
                <p>
                    Cette interface est un <strong>outil de travail réservé au personnel autorisé</strong> de Groupe Speed Cloud. L'accès est strictement limité aux comptes validés par un administrateur.
                </p>
                <p>
                    <strong>Toute connexion, action et session est enregistrée</strong> et peut faire l'objet d'un audit. Tout accès non autorisé ou usage abusif est passible de sanctions disciplinaires et de poursuites.
                </p>
            </div>
        </div>
    </main>

    <footer class="bottom">
        <span>Accès réglementé</span>
        <span class="dot"></span>
        <span>Sessions auditées</span>
        <span class="dot"></span>
        <span>&copy; {{ date('Y') }} Groupe Speed Cloud</span>
    </footer>
</div>

</body>
</html>