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
            display: flex;
            background: #0b0f1a;
            color: #e2e8f0;
        }

        /* ─────────────────────────────────────
           PANNEAU GAUCHE
           ───────────────────────────────────── */
        .left {
            display: none;
            flex: 1;
            position: relative;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            overflow: hidden;
            background: linear-gradient(145deg, #0f1629 0%, #0b0f1a 100%);
        }

        .left::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle 500px at 30% 40%, rgba(124,58,237,0.18), transparent),
                radial-gradient(circle 400px at 70% 70%, rgba(99,102,241,0.12), transparent);
            pointer-events: none;
        }

        .left::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(124,58,237,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124,58,237,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .left-content {
            position: relative;
            z-index: 1;
            max-width: 460px;
            text-align: center;
        }

        .left-logo { margin-bottom: 48px; }

        .left-logo img {
            width: 180px;
            height: auto;
            filter: brightness(0) invert(1) opacity(0.85);
        }

        .left-content h1 {
            font-size: 36px;
            font-weight: 700;
            line-height: 1.15;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin-bottom: 16px;
        }

        .left-content h1 span {
            background: linear-gradient(135deg, #a78bfa, #7c3aed, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .left-content > p {
            font-size: 15px;
            line-height: 1.7;
            color: rgba(255,255,255,0.45);
            margin-bottom: 48px;
        }

        .features {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
            text-align: center;
        }

        .feat {
            padding: 24px 16px;
            border-radius: 14px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            transition: border-color 0.3s, background 0.3s;
        }

        .feat:hover {
            background: rgba(124,58,237,0.06);
            border-color: rgba(124,58,237,0.18);
        }

        .feat-icon {
            width: 44px;
            height: 44px;
            margin: 0 auto 14px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feat-icon.v { background: rgba(124,58,237,0.12); color: #a78bfa; }
        .feat-icon.b { background: rgba(99,102,241,0.12); color: #818cf8; }
        .feat-icon.g { background: rgba(16,185,129,0.12); color: #6ee7b7; }

        .feat strong {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .feat small {
            font-size: 11px;
            color: rgba(255,255,255,0.38);
            line-height: 1.45;
        }

        /* ─────────────────────────────────────
           PANNEAU DROIT
           ───────────────────────────────────── */
        .right {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 32px;
            position: relative;
        }

        .right::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle 400px at 50% 30%, rgba(124,58,237,0.08), transparent);
            pointer-events: none;
        }

        .form-box {
            width: 100%;
            max-width: 380px;
            position: relative;
            z-index: 1;
        }

        .mobile-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        .mobile-logo img {
            width: 140px;
            height: auto;
            filter: brightness(0) invert(1) opacity(0.88);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 14px;
            border-radius: 20px;
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.18);
            font-size: 11px;
            font-weight: 600;
            color: #6ee7b7;
            margin-bottom: 24px;
        }

        .pill-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10b981;
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%      { opacity: 0.3; }
        }

        .form-box h2 {
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }

        .form-box .desc {
            font-size: 14px;
            color: rgba(255,255,255,0.42);
            line-height: 1.65;
            margin-bottom: 32px;
        }

        /* Alertes */
        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            border: 1px solid;
        }

        .alert svg { flex-shrink: 0; }

        .alert-error {
            background: rgba(239,68,68,0.08);
            border-color: rgba(239,68,68,0.18);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(16,185,129,0.08);
            border-color: rgba(16,185,129,0.18);
            color: #6ee7b7;
        }

        /* Bouton */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            width: 100%;
            padding: 15px 24px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            font-family: inherit;
            cursor: pointer;
            border: none;
            color: #ffffff;
            background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);
            box-shadow: 0 4px 20px rgba(124,58,237,0.30);
            transition: all 0.25s cubic-bezier(.4,0,.2,1);
        }

        .btn-google:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(124,58,237,0.40);
        }

        .btn-google:active { transform: translateY(0); }

        .g-badge {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Séparateur */
        .sep {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 28px 0 24px;
        }

        .sep::before, .sep::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.06);
        }

        .sep span {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.18);
        }

        /* Tags */
        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 28px;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.40);
        }

        .tag svg { opacity: 0.45; flex-shrink: 0; }

        .foot {
            font-size: 12px;
            color: rgba(255,255,255,0.22);
            line-height: 1.65;
        }

        .foot strong { color: rgba(255,255,255,0.42); font-weight: 600; }

        /* ─────────────────────────────────────
           RESPONSIVE
           ───────────────────────────────────── */
        @media (min-width: 1024px) {
            .left  { display: flex; }
            .right { max-width: 520px; }
            .mobile-logo { display: none; }
        }

        @media (max-width: 520px) {
            .right { padding: 32px 20px; }
            .form-box h2 { font-size: 22px; }
            .mobile-logo img { width: 120px; }
        }

        /* Entrée */
        @keyframes enter {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .left-content { animation: enter 0.5s ease both 0.1s; }
        .form-box     { animation: enter 0.4s ease both 0.15s; }
    </style>
</head>
<body>

<!-- GAUCHE -->
<aside class="left">
    <div class="left-content">
        <div class="left-logo">
            <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
        </div>

        <h1>Pilotez votre<br><span>infrastructure cPanel</span></h1>
        <p>Interface d'administration centralisée pour la gestion sécurisée de vos serveurs, comptes et services.</p>

        <div class="features">
            <div class="feat">
                <div class="feat-icon v">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L3 7v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V7l-9-5z"/><path d="M9 12l2 2 4-4"/></svg>
                </div>
                <strong>Auth Google</strong>
                <small>SSO professionnel</small>
            </div>
            <div class="feat">
                <div class="feat-icon b">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/><circle cx="12" cy="16" r="1"/></svg>
                </div>
                <strong>Rotation auto</strong>
                <small>Mots de passe / 4h</small>
            </div>
            <div class="feat">
                <div class="feat-icon g">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <strong>Audit complet</strong>
                <small>Sessions tracées</small>
            </div>
        </div>
    </div>
</aside>

<!-- DROITE -->
<main class="right">
    <div class="form-box">
        <div class="mobile-logo">
            <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
        </div>

        <div class="pill">
            <span class="pill-dot"></span>
            Système opérationnel
        </div>

        @if(session('error'))
            <div class="alert alert-error">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <h2>Connexion</h2>
        <p class="desc">Identifiez-vous avec votre compte Google professionnel pour accéder au panel d'administration.</p>

        <a href="{{ route('auth.google') }}" class="btn-google">
            <span class="g-badge">
                <svg width="18" height="18" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
            </span>
            Continuer avec Google
        </a>

        <div class="sep"><span>Sécurité</span></div>

        <div class="tags">
            <span class="tag">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2L3 7v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V7l-9-5z"/></svg>
                SSL / TLS
            </span>
            <span class="tag">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="7" r="4"/><path d="M5.5 21c0-4 3-7 6.5-7s6.5 3 6.5 7"/></svg>
                Accès sur invitation
            </span>
            <span class="tag">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Surveillance temps réel
            </span>
        </div>

        <p class="foot">
            Seuls les comptes <strong>autorisés par un administrateur</strong> peuvent accéder à cette interface. Toutes les tentatives sont enregistrées.
        </p>
    </div>
</main>

</body>
</html>