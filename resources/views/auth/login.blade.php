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

        body {
            font-family: 'TitilliumWeb', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            background: #f5f6fa;
            color: #1e1e2f;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrap {
            width: 100%;
            max-width: 400px;
            padding: 16px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header img {
            width: 220px;
            height: auto;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #6c6893;
            font-size: 13px;
            letter-spacing: 0.3px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e2dff0;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            text-align: center;
        }

        .card h2 {
            font-size: 16px;
            font-weight: 600;
            color: #1e1e2f;
            margin-bottom: 6px;
        }

        .card .subtitle {
            color: #6c6893;
            font-size: 13px;
            margin-bottom: 24px;
        }

        .btn-google {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 12px 20px;
            background: #fff;
            border: 1px solid #e2dff0;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            color: #1e1e2f;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-google:hover {
            border-color: #8a4dfd;
            background: rgba(138,77,253,0.04);
            box-shadow: 0 2px 8px rgba(138,77,253,0.12);
        }

        .btn-google svg {
            flex-shrink: 0;
        }

        .separator {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0 16px;
            color: #b0adc4;
            font-size: 12px;
        }

        .separator::before, .separator::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2dff0;
        }

        .footer-note {
            font-size: 12px;
            color: #b0adc4;
            line-height: 1.5;
        }

        .alert {
            padding: 11px 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            text-align: left;
        }

        .alert-error   { background: #fce8ea; border-left: 4px solid #dc3545; color: #8b1a2b; }
        .alert-success { background: #e8f5ef; border-left: 4px solid #198754; color: #155d38; }
    </style>
</head>
<body>

<div class="login-wrap">
    <div class="login-header">
        <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
        <p>Accès technicien</p>
    </div>

    <div class="card">
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h2>Connexion</h2>
        <p class="subtitle">Authentification requise via votre compte Google professionnel.</p>

        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg width="20" height="20" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            Se connecter avec Google
        </a>

        <div class="separator">réservé aux techniciens</div>

        <p class="footer-note">
            Seuls les comptes autorisés par un administrateur peuvent accéder à ce panel.
        </p>
    </div>
</div>

</body>
</html>
