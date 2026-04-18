<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Groupe Speed Cloud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Google+Sans+Text:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Google Sans Text', 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            min-height: 100vh;
            background: #f8f9fa;
            color: #1f1f1f;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        .material-symbols-rounded {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'opsz' 24;
            vertical-align: middle;
        }

        .wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
        }

        .surface {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            border: 1px solid #dadce0;
            border-radius: 28px;
            padding: 40px 32px 32px;
            animation: reveal 0.3s cubic-bezier(0.2, 0, 0, 1) both;
        }

        @keyframes reveal {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo img {
            height: 28px;
            width: auto;
        }

        .chip {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

        .chip-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            border-radius: 8px;
            background: #d3e3fd;
            color: #041e49;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .chip-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #1a73e8;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            font-weight: 400;
            font-family: 'Google Sans', 'Roboto', sans-serif;
            color: #1f1f1f;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            color: #5f6368;
            margin-bottom: 32px;
            letter-spacing: 0.25px;
        }

        .alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert .material-symbols-rounded { flex-shrink: 0; font-size: 20px; }
        .alert-error   { background: #fce8e6; color: #5f2120; }
        .alert-success { background: #ceead6; color: #0d652d; }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 14px 24px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            font-family: 'Google Sans Text', inherit;
            cursor: pointer;
            color: #ffffff;
            background: #1a73e8;
            border: none;
            letter-spacing: 0.1px;
            transition: box-shadow 0.2s cubic-bezier(0.2, 0, 0, 1), background 0.2s;
        }

        .btn-google:hover {
            box-shadow: 0 1px 2px 0 rgba(60,64,67,0.30), 0 1px 3px 1px rgba(60,64,67,0.15);
            background: #1557b0;
        }

        .btn-google svg { flex-shrink: 0; }

        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 24px 0;
            color: #5f6368;
            font-size: 12px;
            letter-spacing: 0.4px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #dadce0;
        }

        .notice {
            display: flex;
            gap: 12px;
            padding: 16px;
            border-radius: 12px;
            background: #fef7e0;
        }

        .notice-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #feefc3;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #3c3000;
        }

        .notice-text {
            font-size: 12px;
            line-height: 1.6;
            color: #5f6368;
            letter-spacing: 0.4px;
        }

        .notice-text strong { color: #1f1f1f; font-weight: 600; }

        .footer {
            text-align: center;
            padding: 16px 24px 28px;
            font-size: 12px;
            color: #5f6368;
            letter-spacing: 0.4px;
        }

        @media (max-width: 480px) {
            .wrapper { padding: 24px 16px; }
            .surface { padding: 32px 24px 24px; border-radius: 20px; }
            h1 { font-size: 22px; }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="surface">
        <div class="logo">
            <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
        </div>

        <div class="chip">
            <span class="chip-label"><span class="chip-dot"></span> Usage interne</span>
        </div>

        @if(session('error'))
            <div class="alert alert-error">
                <span class="material-symbols-rounded">error</span>
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <span class="material-symbols-rounded">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <h1>Espace restreint</h1>
        <p class="subtitle">Authentification requise via compte professionnel.</p>

        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg width="18" height="18" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#fff" fill-opacity="0.9"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#fff" fill-opacity="0.7"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#fff" fill-opacity="0.5"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#fff" fill-opacity="0.8"/>
            </svg>
            Continuer avec Google
        </a>

        <div class="divider">Accès réglementé</div>

        <div class="notice">
            <div class="notice-icon">
                <span class="material-symbols-rounded" style="font-size:20px;">warning</span>
            </div>
            <div class="notice-text">
                Réservé au <strong>personnel autorisé</strong>. Toute connexion est <strong>enregistrée et auditable</strong>. Un usage non autorisé engage votre responsabilité.
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    Sessions auditées · &copy; {{ date('Y') }} Groupe Speed Cloud
</footer>

</body>
</html>