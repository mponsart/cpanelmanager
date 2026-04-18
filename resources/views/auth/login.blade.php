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
            background: #101828;
            color: #f9fafb;
            display: flex;
            flex-direction: column;
        }

        /* ── Barre accent haute ── */
        .accent-bar {
            height: 3px;
            background: linear-gradient(90deg, #7c3aed, #6366f1, #7c3aed);
        }

        /* ── Layout ── */
        .wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
        }

        .container {
            width: 100%;
            max-width: 380px;
            animation: reveal 0.3s ease both;
        }

        @keyframes reveal {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Logo ── */
        .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo img {
            height: 30px;
            width: auto;
            filter: brightness(0) invert(1) opacity(0.80);
        }

        /* ── Badge Interne ── */
        .badge-row {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .badge-internal {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 6px;
            background: rgba(124,58,237,0.12);
            border: 1px solid rgba(124,58,237,0.22);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            color: #a78bfa;
        }

        .badge-internal .dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #7c3aed;
        }

        /* ── Titre ── */
        .container h1 {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
            letter-spacing: -0.2px;
        }

        .container .desc {
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 28px;
        }

        /* ── Alertes ── */
        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
            font-weight: 500;
        }

        .alert svg { flex-shrink: 0; }
        .alert-error   { background: rgba(239,68,68,0.10); color: #fca5a5; }
        .alert-success { background: rgba(16,185,129,0.10); color: #6ee7b7; }

        /* ── Bouton ── */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 13px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            font-family: inherit;
            cursor: pointer;
            color: #111827;
            background: #f9fafb;
            border: 1px solid #d1d5db;
            transition: background 0.12s, border-color 0.12s, box-shadow 0.12s;
        }

        .btn-google:hover {
            background: #ffffff;
            border-color: #a78bfa;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.12);
        }

        .btn-google svg { flex-shrink: 0; }

        /* ── Séparateur ── */
        .hr {
            height: 1px;
            background: rgba(255,255,255,0.06);
            margin: 24px 0;
        }

        /* ── Notice ── */
        .notice {
            display: flex;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 8px;
            background: rgba(245,158,11,0.06);
            border: 1px solid rgba(245,158,11,0.14);
        }

        .notice-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(245,158,11,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #fbbf24;
        }

        .notice-text {
            font-size: 11.5px;
            line-height: 1.55;
            color: #9ca3af;
        }

        .notice-text strong { color: #d1d5db; font-weight: 600; }

        /* ── Footer ── */
        .footer {
            text-align: center;
            padding: 16px 24px 24px;
            font-size: 11px;
            color: #4b5563;
            letter-spacing: 0.2px;
        }

        @media (max-width: 480px) {
            .wrapper { padding: 24px 16px; }
            .container h1 { font-size: 20px; }
            .logo img { height: 26px; }
        }
    </style>
</head>
<body>

<div class="accent-bar"></div>

<div class="wrapper">
    <div class="container">
        <div class="logo">
            <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
        </div>

        <div class="badge-row">
            <span class="badge-internal"><span class="dot"></span> Usage interne</span>
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

        <h1>Espace restreint</h1>
        <p class="desc">Authentification requise via compte professionnel.</p>

        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg width="18" height="18" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Continuer avec Google
        </a>

        <div class="hr"></div>

        <div class="notice">
            <div class="notice-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="notice-text">
                Réservé au <strong>personnel autorisé</strong>. Toute connexion est <strong>enregistrée et auditable</strong>. Un usage non autorisé engage votre responsabilité.
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    Accès réglementé · Sessions auditées · &copy; {{ date('Y') }} Groupe Speed Cloud
</footer>

</body>
</html>