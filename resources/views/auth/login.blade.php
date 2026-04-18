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
            background: #f8f9fb;
            color: #1e293b;
            padding: 24px;
        }

        .login {
            width: 100%;
            max-width: 420px;
            animation: up 0.4s ease both;
        }

        @keyframes up {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Logo */
        .login-logo {
            text-align: center;
            margin-bottom: 36px;
        }

        .login-logo img {
            width: 140px;
            height: auto;
        }

        /* Carte */
        .login-card {
            background: #ffffff;
            border: 1px solid #e8ecf1;
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 32px rgba(0,0,0,0.03);
        }

        .login-card h1 {
            font-size: 21px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .login-card .desc {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.55;
            margin-bottom: 28px;
        }

        /* Alertes */
        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid;
        }

        .alert svg { flex-shrink: 0; }

        .alert-error {
            background: #fef2f2;
            border-color: #fee2e2;
            color: #dc2626;
        }

        .alert-success {
            background: #f0fdf4;
            border-color: #dcfce7;
            color: #16a34a;
        }

        /* Bouton Google */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 13px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            font-family: inherit;
            cursor: pointer;
            color: #0f172a;
            background: #ffffff;
            border: 1px solid #d4d8e0;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .btn-google:hover {
            background: #f8f9fb;
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.08), 0 1px 2px rgba(0,0,0,0.05);
        }

        .btn-google:active {
            background: #f1f5f9;
        }

        .btn-google svg { flex-shrink: 0; }

        /* Séparateur */
        .or {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0 18px;
        }

        .or::before, .or::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e8ecf1;
        }

        .or span {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Infos */
        .info-row {
            display: flex;
            gap: 10px;
        }

        .info-item {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 8px;
            background: #f8f9fb;
            border: 1px solid #eef0f4;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
        }

        .info-item svg { flex-shrink: 0; color: #7c3aed; }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.6;
        }

        @media (max-width: 480px) {
            .login-card { padding: 32px 24px; }
            .login-card h1 { font-size: 19px; }
            .login-logo img { width: 120px; }
            .info-row { flex-direction: column; }
        }
    </style>
</head>
<body>

<div class="login">
    <div class="login-logo">
        <img src="/images/logo.svg" alt="Groupe Speed Cloud">
    </div>

    <div class="login-card">
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

        <h1>Connexion au panel</h1>
        <p class="desc">Utilisez votre compte Google professionnel pour continuer.</p>

        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg width="18" height="18" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Continuer avec Google
        </a>

        <div class="or"><span>Accès sécurisé</span></div>

        <div class="info-row">
            <div class="info-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2L3 7v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V7l-9-5z"/></svg>
                SSL / TLS
            </div>
            <div class="info-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Sur invitation
            </div>
            <div class="info-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Audité
            </div>
        </div>
    </div>

    <p class="login-footer">
        Réservé aux comptes autorisés — les tentatives sont enregistrées.
    </p>
</div>

</body>
</html>