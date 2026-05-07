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
            background:
                radial-gradient(circle at 15% 15%, rgba(26,115,232,0.14), transparent 38%),
                radial-gradient(circle at 85% 85%, rgba(30,142,62,0.12), transparent 36%),
                #f4f7fb;
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
            padding: 30px 20px;
        }

        .shell {
            width: 100%;
            max-width: 980px;
            border: 1px solid #d3d9e3;
            border-radius: 32px;
            background: #fff;
            box-shadow: 0 16px 40px rgba(60,64,67,.16), 0 3px 10px rgba(60,64,67,.1);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            min-height: 560px;
        }

        .brand-pane {
            background: linear-gradient(155deg, #eef3fd 0%, #f7faff 60%, #fefefe 100%);
            border-right: 1px solid #e8eaed;
            padding: 36px 34px;
            display: flex;
            flex-direction: column;
        }

        .logo img { height: 28px; width: auto; display: block; }

        .chip {
            margin-top: 20px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            border-radius: 999px;
            padding: 6px 12px;
            background: #d3e3fd;
            color: #0b3a83;
            font-size: 11px;
            letter-spacing: .45px;
            text-transform: uppercase;
            font-weight: 700;
            width: fit-content;
        }

        .chip-dot { width: 6px; height: 6px; border-radius: 50%; background: #1a73e8; }

        .brand-title {
            margin-top: 26px;
            font-family: 'Google Sans', 'Roboto', sans-serif;
            font-size: 30px;
            line-height: 1.15;
            font-weight: 500;
            color: #202124;
            max-width: 340px;
        }

        .brand-subtitle {
            margin-top: 12px;
            color: #5f6368;
            font-size: 14px;
            line-height: 1.6;
            max-width: 320px;
        }

        .brand-notice {
            margin-top: auto;
            display: flex;
            gap: 11px;
            border: 1px solid #fce8b2;
            border-radius: 14px;
            background: #fef7e0;
            padding: 14px;
            font-size: 12px;
            color: #6a4a00;
            line-height: 1.55;
        }

        .brand-notice strong { color: #3c3000; }

        .auth-pane {
            padding: 36px 34px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h1 {
            font-family: 'Google Sans', 'Roboto', sans-serif;
            font-size: 30px;
            font-weight: 400;
            color: #202124;
            margin-bottom: 6px;
            letter-spacing: -.2px;
        }

        .subtitle {
            font-size: 14px;
            color: #5f6368;
            margin-bottom: 22px;
            line-height: 1.5;
        }

        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 12px;
            padding: 11px 13px;
            margin-bottom: 12px;
            font-size: 13px;
            font-weight: 500;
        }

        .alert-error { background: #fce8e6; color: #5f2120; }
        .alert-success { background: #e6f4ea; color: #0d652d; }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 11px;
            width: 100%;
            padding: 13px 20px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            color: #fff;
            background: #1a73e8;
            border: none;
            transition: box-shadow .2s ease, background .2s ease;
        }

        .btn-google:hover {
            background: #1557b0;
            box-shadow: 0 1px 2px rgba(60,64,67,.3), 0 2px 6px rgba(60,64,67,.15);
        }

        .btn-google:disabled {
            background: #9aa0a6;
            cursor: not-allowed;
            box-shadow: none;
        }

        .footer {
            text-align: center;
            padding: 10px 18px 24px;
            font-size: 12px;
            color: #5f6368;
            letter-spacing: .3px;
        }

        @media (max-width: 900px) {
            .shell { grid-template-columns: 1fr; max-width: 540px; }
            .brand-pane { border-right: none; border-bottom: 1px solid #e8eaed; padding: 28px 24px; }
            .brand-title { font-size: 26px; max-width: none; }
            .brand-subtitle { max-width: none; }
            .brand-notice { margin-top: 18px; }
            .auth-pane { padding: 28px 24px; }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="shell">
        <aside class="brand-pane">
            <div class="logo">
                <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
            </div>
            <span class="chip"><span class="chip-dot"></span> Usage interne</span>

            <h2 class="brand-title">Portail d'administration technique</h2>
            <p class="brand-subtitle">Authentification sécurisée via compte professionnel Google. L'accès est réservé aux personnes autorisées.</p>

            <div class="brand-notice">
                <span class="material-symbols-rounded" style="font-size:20px;">warning</span>
                <div>
                    Toute action est <strong>journalisée et auditable</strong>.
                    Le non-respect des règles de sécurité engage votre responsabilité.
                </div>
            </div>
        </aside>

        <section class="auth-pane">
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

            <h1>Connexion</h1>
            <p class="subtitle">Connectez-vous avec votre compte professionnel Google.</p>

            <form action="{{ route('auth.google') }}" method="POST" class="auth-form">
                @csrf
                <button type="submit" class="btn-google">
                    <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#fff" fill-opacity="0.9"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#fff" fill-opacity="0.7"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#fff" fill-opacity="0.5"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#fff" fill-opacity="0.8"/>
                    </svg>
                    Continuer avec Google
                </button>
            </form>
        </section>
    </div>
</div>

<footer class="footer">
    Sessions auditées · &copy; {{ date('Y') }} Groupe Speed Cloud
</footer>

</body>
</html>
