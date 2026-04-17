<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Accès Technicien</title>
    <style>
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Regular.ttf') format('truetype'); font-weight:400; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-SemiBold.ttf') format('truetype'); font-weight:600; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Bold.ttf') format('truetype'); font-weight:700; }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --accent: #2563eb;
            --text: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }

        body {
            font-family: 'TitilliumWeb', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            min-height: 100vh;
            color: var(--text);
            background: #0f172a;
            display: flex;
            align-items: stretch;
        }

        .auth-brand {
            flex: 1;
            display: none;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 52px;
            background:
                radial-gradient(circle at 20% 80%, rgba(37,99,235,0.35), transparent 50%),
                radial-gradient(circle at 80% 10%, rgba(99,102,241,0.25), transparent 45%),
                #0f172a;
            border-right: 1px solid rgba(255,255,255,0.06);
            position: relative;
            overflow: hidden;
        }

        .auth-brand::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.04) 1px, transparent 0);
            background-size: 32px 32px;
            pointer-events: none;
        }

        .auth-brand img { width: 160px; height: auto; position: relative; z-index: 1; }

        .auth-brand-body { position: relative; z-index: 1; }

        .auth-brand-body h1 {
            font-size: 36px;
            font-weight: 700;
            color: #f1f5f9;
            line-height: 1.15;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .auth-brand-body p {
            font-size: 15px;
            line-height: 1.65;
            color: #94a3b8;
            max-width: 380px;
        }

        .auth-chips { display: flex; flex-wrap: wrap; gap: 8px; position: relative; z-index: 1; }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.06);
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
        }

        .chip-dot { width: 6px; height: 6px; border-radius: 50%; background: #3b82f6; }

        .auth-panel {
            width: 100%;
            max-width: 480px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 40px;
            min-height: 100vh;
        }

        .auth-logo-mobile { display: block; margin-bottom: 32px; }
        .auth-logo-mobile img { width: 130px; height: auto; filter: brightness(0); }

        .auth-panel h2 {
            font-size: 26px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .subtitle { color: var(--text-muted); font-size: 14px; line-height: 1.6; margin-bottom: 32px; }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 13px 18px;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 600;
            text-decoration: none;
            color: var(--text);
            background: #fff;
            border: 1.5px solid var(--border);
            transition: all 0.14s;
            font-family: inherit;
            cursor: pointer;
        }

        .btn-google:hover {
            border-color: #93c5fd;
            background: #eff6ff;
            box-shadow: 0 4px 16px rgba(37,99,235,0.12);
            transform: translateY(-1px);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 28px 0;
            color: #cbd5e1;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }

        .footer-note { font-size: 13px; color: var(--text-muted); line-height: 1.6; }

        .alert { padding: 11px 14px; border-radius: 8px; margin-bottom: 22px; font-size: 13px; border: 1px solid; }
        .alert-error   { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }
        .alert-success { background: #f0fdf4; border-color: #bbf7d0; color: #15803d; }

        @media (min-width: 900px) {
            .auth-brand { display: flex; flex: 1; }
            .auth-logo-mobile { display: none; }
        }

        @media (max-width: 520px) {
            .auth-panel { padding: 32px 22px; }
            .auth-panel h2 { font-size: 22px; }
        }
    </style>
</head>
<body>

<aside class="auth-brand">
    <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
    <div class="auth-brand-body">
        <h1>Panel d'administration cPanel</h1>
        <p>Gérez l'ensemble des services techniques depuis une interface unifiée, moderne et sécurisée.</p>
    </div>
    <div class="auth-chips">
        <span class="chip"><span class="chip-dot"></span> Auth Google</span>
        <span class="chip"><span class="chip-dot"></span> Accès techniciens</span>
        <span class="chip"><span class="chip-dot"></span> Journalisation complète</span>
    </div>
</aside>

<main class="auth-panel">
    <div class="auth-logo-mobile">
        <img src="/images/logo.svg" alt="Groupe Speed Cloud">
    </div>

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h2>Connexion</h2>
    <p class="subtitle">Authentifiez-vous avec votre compte Google professionnel pour accéder au panel.</p>

    <a href="{{ route('auth.google') }}" class="btn-google">
        <svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Se connecter avec Google
    </a>

    <div class="divider">Espace sécurisé</div>

    <p class="footer-note">
        Seuls les comptes autorisés par un administrateur peuvent accéder à ce panel.
    </p>
</main>

</body>
</html>
