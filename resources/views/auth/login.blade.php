<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Accès Technicien</title>
    <style>
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Light.ttf') format('truetype'); font-weight:300; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Regular.ttf') format('truetype'); font-weight:400; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-SemiBold.ttf') format('truetype'); font-weight:600; }
        @font-face { font-family:'TitilliumWeb'; src:url('/fonts/TitilliumWeb-Bold.ttf') format('truetype'); font-weight:700; }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #f3f4fb;
            --panel: #ffffff;
            --panel-soft: #f8f7ff;
            --border: #e4def8;
            --text: #1e1e31;
            --text-muted: #6e6b8d;
            --accent: #8a4dfd;
            --accent-strong: #6c2ff2;
        }

        body {
            font-family: 'TitilliumWeb', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            min-height: 100vh;
            color: var(--text);
            background:
                radial-gradient(circle at 12% 18%, rgba(138, 77, 253, 0.22), transparent 38%),
                radial-gradient(circle at 86% 0%, rgba(79, 124, 255, 0.20), transparent 34%),
                var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 14px;
        }

        .auth-shell {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: 1fr minmax(21rem, 26rem);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.88);
            box-shadow: 0 24px 48px rgba(37, 29, 76, 0.14);
            backdrop-filter: blur(12px);
        }

        .auth-brand {
            padding: 48px 44px;
            background:
                radial-gradient(circle at 0% 0%, rgba(255,255,255,0.26), transparent 44%),
                linear-gradient(145deg, #8a4dfd, #5f2fe5);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 24px;
        }

        .auth-brand img {
            width: 210px;
            max-width: 100%;
        }

        .auth-brand h1 {
            font-size: 34px;
            line-height: 1.1;
            letter-spacing: 0.2px;
            margin-bottom: 14px;
        }

        .auth-brand p {
            max-width: 420px;
            line-height: 1.55;
            font-size: 15px;
            color: rgba(255, 255, 255, 0.96);
        }

        .auth-brand .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.28);
            background: rgba(255, 255, 255, 0.12);
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.92);
        }

        .auth-panel {
            padding: 40px 34px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: linear-gradient(180deg, #fff, #fdfcff);
        }

        .auth-panel h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .subtitle {
            color: var(--text-muted);
            margin-bottom: 24px;
            font-size: 14px;
        }

        .btn-google {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 12px 18px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            color: var(--text);
            background: var(--panel-soft);
            border: 1px solid var(--border);
            transition: all 0.15s;
        }

        .btn-google:hover {
            border-color: #bf9fff;
            background: #fff;
            box-shadow: 0 10px 18px rgba(138, 77, 253, 0.16);
            transform: translateY(-1px);
        }

        .btn-google svg { flex-shrink: 0; }

        .separator {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0 16px;
            color: #b0adc4;
            font-size: 12px;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .separator::before, .separator::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2dff0;
        }

        .footer-note {
            font-size: 13px;
            color: #8d89a9;
            line-height: 1.55;
        }

        .alert {
            padding: 11px 14px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 13px;
            border: 1px solid;
        }

        .alert-error   { background: #fdebec; border-color: #f3b6bd; color: #8b1a2b; }
        .alert-success { background: #e8f5ef; border-color: #b8e5c9; color: #155d38; }

        @media (max-width: 930px) {
            .auth-shell {
                grid-template-columns: 1fr;
                max-width: 560px;
            }

            .auth-brand {
                padding: 28px 24px;
                gap: 18px;
            }

            .auth-brand h1 {
                font-size: 26px;
                margin-bottom: 10px;
            }
        }

        @media (max-width: 520px) {
            body { padding: 10px; }
            .auth-shell { border-radius: 16px; }
            .auth-brand, .auth-panel { padding: 20px 16px; }
            .auth-panel h2 { font-size: 21px; }
            .btn-google { font-size: 14px; padding: 11px 14px; }
        }
    </style>
</head>
<body>
<div class="auth-shell">
    <aside class="auth-brand">
        <div>
            <img src="/images/logo.svg" alt="Groupe Speed Cloud">
        </div>
        <div>
            <h1>Panel d'administration cPanel</h1>
            <p>Gérez l’ensemble des services techniques depuis une interface unifiée, moderne et sécurisée.</p>
        </div>
        <div class="meta">
            <span class="meta-chip">Authentification Google</span>
            <span class="meta-chip">Accès techniciens</span>
        </div>
    </aside>

    <main class="auth-panel">
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h2>Connexion</h2>
        <p class="subtitle">Authentifiez-vous avec votre compte Google professionnel pour accéder au panel.</p>

        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg width="20" height="20" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            Se connecter avec Google
        </a>

        <div class="separator">Espace sécurisé</div>

        <p class="footer-note">
            Seuls les comptes autorisés par un administrateur peuvent accéder à ce panel.
        </p>
    </main>
</div>
</body>
</html>
