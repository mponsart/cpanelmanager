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
            --accent:      #a855f7;
            --accent-strong: #9333ea;
            --text:        #e8eaf0;
            --text-muted:  #6b7280;
            --border:      rgba(168,85,247,0.15);
        }

        body {
            font-family: 'TitilliumWeb', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            min-height: 100vh;
            color: var(--text);
            background: #0b0b14;
            display: flex;
            align-items: stretch;
        }

        /* ── Left brand panel ── */
        .auth-brand {
            flex: 1;
            display: none;
            flex-direction: column;
            justify-content: space-between;
            padding: 52px 56px;
            background:
                radial-gradient(ellipse at 15% 85%, rgba(168,85,247,0.30) 0%, transparent 55%),
                radial-gradient(ellipse at 85% 10%, rgba(99,102,241,0.22) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(139,92,246,0.08) 0%, transparent 70%),
                #0b0b14;
            border-right: 1px solid rgba(168,85,247,0.10);
            position: relative;
            overflow: hidden;
        }

        /* Dot grid pattern */
        .auth-brand::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 1px 1px, rgba(168,85,247,0.08) 1px, transparent 0);
            background-size: 28px 28px;
            pointer-events: none;
        }

        /* Decorative glow orb */
        .auth-brand::after {
            content: '';
            position: absolute;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(168,85,247,0.18) 0%, transparent 70%);
            bottom: -80px;
            left: -80px;
            pointer-events: none;
        }

        .auth-brand img {
            width: 150px;
            height: auto;
            position: relative;
            z-index: 1;
            filter: brightness(0) invert(1) opacity(0.88);
        }

        .auth-brand-body { position: relative; z-index: 1; }

        .auth-brand-body h1 {
            font-size: 38px;
            font-weight: 700;
            color: #f1f5f9;
            line-height: 1.15;
            margin-bottom: 18px;
            letter-spacing: -0.6px;
        }

        .auth-brand-body h1 span {
            background: linear-gradient(135deg, #c084fc, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-brand-body p {
            font-size: 15px;
            line-height: 1.7;
            color: #94a3b8;
            max-width: 380px;
        }

        .auth-chips { display: flex; flex-wrap: wrap; gap: 8px; position: relative; z-index: 1; }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 20px;
            border: 1px solid rgba(168,85,247,0.20);
            background: rgba(168,85,247,0.08);
            font-size: 12px;
            font-weight: 600;
            color: #c4b5fd;
            backdrop-filter: blur(4px);
        }

        .chip-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 8px rgba(168,85,247,0.8);
        }

        /* ── Right form panel ── */
        .auth-panel {
            width: 100%;
            max-width: 460px;
            background: #111118;
            border-left: 1px solid rgba(168,85,247,0.08);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 52px 44px;
            min-height: 100vh;
        }

        .auth-logo-mobile { display: block; margin-bottom: 36px; }
        .auth-logo-mobile img { width: 120px; height: auto; filter: brightness(0) invert(1) opacity(0.85); }

        .auth-panel h2 {
            font-size: 26px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.65;
            margin-bottom: 36px;
        }

        /* Google sign-in button */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 14px 18px;
            border-radius: 11px;
            font-size: 14.5px;
            font-weight: 600;
            text-decoration: none;
            color: #f1f5f9;
            background: rgba(168,85,247,0.10);
            border: 1px solid rgba(168,85,247,0.28);
            transition: all 0.16s;
            font-family: inherit;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .btn-google::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(168,85,247,0.10), rgba(99,102,241,0.08));
            opacity: 0;
            transition: opacity 0.16s;
        }

        .btn-google:hover {
            border-color: rgba(168,85,247,0.55);
            box-shadow: 0 0 24px rgba(168,85,247,0.22), inset 0 0 20px rgba(168,85,247,0.06);
            transform: translateY(-1px);
        }

        .btn-google:hover::before { opacity: 1; }

        .btn-google svg { position: relative; z-index: 1; flex-shrink: 0; }
        .btn-google span { position: relative; z-index: 1; }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 30px 0;
            color: #374151;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(168,85,247,0.12);
        }

        .footer-note {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.65;
        }

        .footer-note strong { color: #9ca3af; }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 13px;
            border: 1px solid;
        }

        .alert-error   { background: rgba(248,113,113,0.08); border-color: rgba(248,113,113,0.25); color: #fca5a5; }
        .alert-success { background: rgba(52,211,153,0.08); border-color: rgba(52,211,153,0.25); color: #6ee7b7; }

        @media (min-width: 900px) {
            .auth-brand { display: flex; flex: 1; }
            .auth-logo-mobile { display: none; }
        }

        @media (max-width: 520px) {
            .auth-panel { padding: 36px 24px; }
            .auth-panel h2 { font-size: 22px; }
        }
    </style>
</head>
<body>

<aside class="auth-brand">
    <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
    <div class="auth-brand-body">
        <h1>Panel d'administration<br><span>cPanel</span></h1>
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
        <span>Se connecter avec Google</span>
    </a>

    <div class="divider">Espace sécurisé</div>

    <p class="footer-note">
        Seuls les comptes <strong>autorisés par un administrateur</strong> peuvent accéder à ce panel.
    </p>
</main>

</body>
</html>
