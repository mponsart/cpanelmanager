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
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0b0f1a;
            color: #e2e8f0;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(circle 600px at 50% 35%, rgba(124,58,237,0.08), transparent);
            pointer-events: none;
        }

        .card {
            width: 100%;
            max-width: 400px;
            padding: 44px 36px;
            margin: 24px;
            border-radius: 16px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            position: relative;
            z-index: 1;
            animation: enter 0.35s ease both;
        }

        @keyframes enter {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 32px;
        }

        .logo img {
            width: 150px;
            height: auto;
            filter: brightness(0) invert(1) opacity(0.85);
        }

        .card h1 {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .card .sub {
            text-align: center;
            font-size: 13px;
            color: rgba(255,255,255,0.40);
            line-height: 1.6;
            margin-bottom: 32px;
        }

        /* Alertes */
        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            border-radius: 9px;
            margin-bottom: 18px;
            font-size: 13px;
            border: 1px solid;
        }

        .alert svg { flex-shrink: 0; }
        .alert-error   { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.16); color: #fca5a5; }
        .alert-success { background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.16); color: #6ee7b7; }

        /* Bouton */
        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 14px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            font-family: inherit;
            cursor: pointer;
            border: none;
            color: #fff;
            background: linear-gradient(135deg, #7c3aed, #6366f1);
            box-shadow: 0 3px 14px rgba(124,58,237,0.25);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(124,58,237,0.35);
        }

        .btn:active { transform: translateY(0); }

        .g-icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
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
            gap: 12px;
            margin: 24px 0 20px;
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
            letter-spacing: 1.8px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.16);
        }

        .note {
            text-align: center;
            font-size: 12px;
            color: rgba(255,255,255,0.25);
            line-height: 1.6;
        }

        .note strong { color: rgba(255,255,255,0.42); font-weight: 600; }

        @media (max-width: 480px) {
            .card { padding: 32px 24px; margin: 16px; }
            .card h1 { font-size: 20px; }
            .logo img { width: 120px; }
        }
    </style>
</head>
<body>

<div class="card">
    <div class="logo">
        <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
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

    <h1>Panel technique</h1>
    <p class="sub">Connectez-vous avec votre compte Google professionnel.</p>

    <a href="{{ route('auth.google') }}" class="btn">
        <span class="g-icon">
            <svg width="16" height="16" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
        </span>
        Continuer avec Google
    </a>

    <div class="sep"><span>Accès restreint</span></div>

    <p class="note">
        Réservé aux comptes <strong>autorisés par un administrateur</strong>.<br>
        Les tentatives de connexion sont enregistrées.
    </p>
</div>

</body>
</html>