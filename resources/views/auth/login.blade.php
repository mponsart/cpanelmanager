<!doctype html>
<html lang="fr" data-fr-scheme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Groupe Speed Cloud</title>

    <!-- DSFR CDN -->
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
    </style>
</head>

<body>

<header role="banner" class="fr-header">
    <div class="fr-container">
        <div class="fr-header__body">
            <div class="fr-header__brand">
                <div class="fr-header__brand-top">
                    <div class="fr-logo">
                        République<br>Française
                    </div>
                </div>
                <div class="fr-header__service">
                    <p class="fr-header__service-title">Groupe Speed Cloud</p>
                    <p class="fr-header__service-tagline">Portail d'administration technique</p>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="login-wrapper">
    <div class="fr-container">
        <div class="fr-grid-row fr-grid-row--center">

            <div class="fr-col-12 fr-col-md-6 fr-col-lg-5">

                <div class="fr-card fr-p-4w">

                    <h1 class="fr-h2">Connexion</h1>
                    <p class="fr-text--sm">
                        Authentification sécurisée via compte professionnel Google.
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

                        <button class="fr-btn fr-btn--lg fr-btn--primary fr-btn--icon-left fr-icon-google-fill">
                            Continuer avec Google
                        </button>
                    </form>

                    <p class="fr-text--xs fr-mt-3w">
                        Usage interne · Sessions auditées et sécurisées
                    </p>

                </div>

            </div>

        </div>
    </div>
</main>

<footer class="fr-footer" role="contentinfo">
    <div class="fr-container">
        <p class="fr-footer__content">
            &copy; {{ date('Y') }} Groupe Speed Cloud
        </p>
    </div>
</footer>

</body>
</html>