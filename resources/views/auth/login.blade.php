<!doctype html>
<html lang="fr" data-fr-scheme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Groupe Speed Cloud</title>

    <!-- DSFR -->
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

        .card-title {
            margin-bottom: 6px;
        }
    </style>
</head>

<body>

<header role="banner" class="fr-header">
    <div class="fr-container">
        <div class="fr-header__body">
            <div class="fr-header__brand">
                <div class="fr-header__brand-top">
                    <div class="fr-logo">
                        Groupe<br>Speed Cloud
                    </div>
                </div>
                <div class="fr-header__service">
                    <p class="fr-header__service-title">Portail d'administration technique</p>
                    <p class="fr-header__service-tagline">Accès sécurisé interne</p>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="login-wrapper">
    <div class="fr-container">
        <div class="fr-grid-row fr-grid-row--center">

            <div class="fr-col-12 fr-col-md-6 fr-col-lg-4">

                <div class="fr-card fr-p-4w">

                    <h1 class="fr-h2 card-title">Connexion</h1>
                    <p class="fr-text--sm">
                        Authentification via compte Google professionnel.
                    </p>

                    {{-- ALERT ERROR --}}
                    @if(session('error'))
                        <div class="fr-alert fr-alert--error fr-mb-2w">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- ALERT SUCCESS --}}
                    @if(session('success'))
                        <div class="fr-alert fr-alert--success fr-mb-2w">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('auth.google') }}" method="POST">
                        @csrf

                        <button type="submit" class="fr-btn fr-btn--primary google-btn fr-mt-2w">

                            <!-- Google SVG simple (stable, sans dépendance DSFR) -->
                            <svg class="google-icon" viewBox="0 0 48 48">
                                <path fill="#EA4335" d="M24 9.5c3.1 0 5.7 1.1 7.8 3.1l5.8-5.8C33.9 3.2 29.4 1.5 24 1.5 14.6 1.5 6.8 7.9 3.9 16.4l6.9 5.4C12.2 14.2 17.6 9.5 24 9.5z"/>
                                <path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-2.8-.4-4H24v8h12.6c-.6 3.2-2.4 5.9-5.2 7.7l6.8 5.3c4-3.7 6.3-9.2 6.3-17z"/>
                                <path fill="#FBBC05" d="M10.8 28.6c-1-3-1-6.2 0-9.2l-6.9-5.4C1.1 17.6 0 20.7 0 24s1.1 6.4 2.9 9.2l6.9-5.6z"/>
                                <path fill="#34A853" d="M24 46.5c5.4 0 9.9-1.8 13.2-4.9l-6.8-5.3c-2 1.4-4.6 2.2-6.4 2.2-6.4 0-11.8-4.7-13.2-11l-6.9 5.6C6.8 40.1 14.6 46.5 24 46.5z"/>
                            </svg>

                            Continuer avec Google
                        </button>

                    </form>

                    <p class="fr-text--xs fr-mt-3w">
                        Usage interne · toutes actions sont journalisées
                    </p>

                </div>

            </div>

        </div>
    </div>
</main>

<footer class="fr-footer">
    <div class="fr-container">
        <p class="fr-footer__content">
            &copy; {{ date('Y') }} Groupe Speed Cloud
        </p>
    </div>
</footer>

</body>
</html>