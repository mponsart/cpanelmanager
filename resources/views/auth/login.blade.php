<!doctype html>
<html lang="fr" data-fr-scheme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Groupe Speed Cloud</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@gouvfr/dsfr@1.11.0/dist/dsfr.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/@gouvfr/dsfr@1.11.0/dist/dsfr.module.min.js"></script>

    <style>
        body {
            background: #f6f8fc;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .google-icon {
            width: 18px;
            height: 18px;
        }
    </style>
</head>

<body>

<main class="login-wrapper">
    <div class="fr-container">
        <div class="fr-grid-row fr-grid-row--center">

            <div class="fr-col-12 fr-col-md-6 fr-col-lg-4">

                <div class="fr-card fr-p-4w">

                    <h1 class="fr-h2">Connexion</h1>
                    <p class="fr-text--sm">
                        Accès sécurisé via compte Google professionnel.
                    </p>

                    @if(session('error'))
                        <div class="fr-alert fr-alert--error fr-mb-2w">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="fr-alert fr-alert--success fr-mb-2w">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('auth.google') }}" method="POST">
                        @csrf

                        <button class="fr-btn fr-btn--primary google-btn" type="submit">
                            <svg class="google-icon" viewBox="0 0 24 24">
                                <path fill="#EA4335" d="M12 11v4h5c-.2 1.3-1.6 3.8-5 3.8-3 0-5.5-2.5-5.5-5.5S9 7.8 12 7.8c1.7 0 2.8.7 3.4 1.3l2.3-2.2C16.2 5.5 14.3 4.5 12 4.5 7.6 4.5 4 8.1 4 12.5S7.6 20.5 12 20.5c7.5 0 8.3-6.5 7.7-9.5H12z"/>
                            </svg>
                            Continuer avec Google
                        </button>

                    </form>

                    <p class="fr-text--xs fr-mt-3w">
                        Usage interne · accès journalisé
                    </p>

                </div>

            </div>

        </div>
    </div>
</main>

</body>
</html>