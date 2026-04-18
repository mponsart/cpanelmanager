<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Groupe Speed Cloud</title>
    <style>
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Light.ttf') format('truetype'); font-weight:300; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Regular.ttf') format('truetype'); font-weight:400; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-SemiBold.ttf') format('truetype'); font-weight:600; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Bold.ttf') format('truetype'); font-weight:700; }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'TitilliumWeb', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            min-height: 100vh;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            overflow: hidden;
            position: relative;
        }

        /* ── Fond animé ── */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(124,58,237,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124,58,237,0.06) 1px, transparent 1px);
            background-size: 48px 48px;
            z-index: 0;
        }

        .bg-glow {
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.35;
            z-index: 0;
            pointer-events: none;
        }

        .bg-glow-1 {
            background: #7c3aed;
            top: -200px;
            left: -100px;
        }

        .bg-glow-2 {
            background: #4c1d95;
            bottom: -250px;
            right: -150px;
            opacity: 0.25;
        }

        /* ── Carte principale ── */
        .login-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            margin: 24px;
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 48px 40px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.3);
        }

        .login-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 36px;
        }

        .login-logo img {
            width: 160px;
            height: auto;
            filter: brightness(0) invert(1) opacity(0.92);
        }

        .login-card h1 {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
            margin-bottom: 8px;
        }

        .login-subtitle {
            text-align: center;
            font-size: 14px;
            color: rgba(255,255,255,0.50);
            line-height: 1.6;
            margin-bottom: 36px;
        }

        /* ── Alertes ── */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            border: 1px solid;
        }

        .alert-error {
            background: rgba(220,38,38,0.12);
            border-color: rgba(220,38,38,0.25);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(5,150,105,0.12);
            border-color: rgba(5,150,105,0.25);
            color: #6ee7b7;
        }

        /* ── Bouton Google ── */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 15px 20px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            color: #0f172a;
            background: #ffffff;
            border: none;
            transition: all 0.2s cubic-bezier(.4,0,.2,1);
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .btn-google:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(124,58,237,0.25), 0 4px 12px rgba(0,0,0,0.2);
        }

        .btn-google:active {
            transform: translateY(0);
        }

        .btn-google svg { flex-shrink: 0; }

        /* ── Séparateur ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 28px 0 24px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.08);
        }

        .divider span {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
        }

        /* ── Badges sécurité ── */
        .security-badges {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-bottom: 28px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(124,58,237,0.10);
            border: 1px solid rgba(124,58,237,0.18);
            font-size: 11px;
            font-weight: 600;
            color: rgba(167,139,250,0.90);
            letter-spacing: 0.2px;
        }

        .badge svg {
            opacity: 0.7;
            flex-shrink: 0;
        }

        /* ── Footer ── */
        .login-footer {
            text-align: center;
            font-size: 12px;
            color: rgba(255,255,255,0.30);
            line-height: 1.6;
        }

        .login-footer strong {
            color: rgba(255,255,255,0.50);
            font-weight: 600;
        }

        /* ── Responsive ── */
        @media (max-width: 520px) {
            .login-card {
                padding: 36px 24px;
                border-radius: 16px;
                margin: 16px;
            }
            .login-card h1 { font-size: 20px; }
            .login-logo img { width: 130px; }
            .security-badges { gap: 6px; }
            .badge { font-size: 10px; padding: 5px 10px; }
        }

        /* ── Animation d'entrée ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            animation: fadeUp 0.5s cubic-bezier(.4,0,.2,1) both;
        }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-glow bg-glow-1"></div>
<div class="bg-glow bg-glow-2"></div>

<div class="login-card">
    <div class="login-logo">
        <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
    </div>

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h1>Panel d'administration</h1>
    <p class="login-subtitle">Authentifiez-vous avec votre compte Google professionnel pour accéder au panel de gestion cPanel.</p>

    <div class="security-badges">
        <span class="badge">
            <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 1.5L2 4v4c0 3 2.7 5.5 6 7 3.3-1.5 6-4 6-7V4L8 1.5z"/></svg>
            Connexion sécurisée
        </span>
        <span class="badge">
            <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="5.5" r="3"/><path d="M1.5 14c0-3 3-5.5 6.5-5.5s6.5 2.5 6.5 5.5"/></svg>
            Accès sur invitation
        </span>
        <span class="badge">
            <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="2" y1="4" x2="14" y2="4"/><line x1="2" y1="8" x2="14" y2="8"/><line x1="2" y1="12" x2="10" y2="12"/></svg>
            Journalisation complète
        </span>
    </div>

    <a href="{{ route('auth.google') }}" class="btn-google">
        <svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        <span>Se connecter avec Google</span>
    </a>

    <div class="divider"><span>Espace réservé</span></div>

    <p class="login-footer">
        Seuls les comptes <strong>autorisés par un administrateur</strong> peuvent accéder à ce panel.
    </p>
</div>

</body>
</html>
