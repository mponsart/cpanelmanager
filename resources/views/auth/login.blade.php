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
            min-height: 100vh;
            display: flex;
            background: #050a18;
            color: #e2e8f0;
            overflow: hidden;
        }

        /* ══════════════════════════════════════
           PANNEAU GAUCHE — Vitrine
           ══════════════════════════════════════ */
        .showcase {
            flex: 1;
            position: relative;
            display: none;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 56px;
            overflow: hidden;
        }

        /* Fond gradient mesh */
        .showcase::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 80%, rgba(124,58,237,0.30), transparent),
                radial-gradient(ellipse 60% 50% at 80% 20%, rgba(99,102,241,0.20), transparent),
                radial-gradient(ellipse 50% 40% at 50% 50%, rgba(139,92,246,0.08), transparent),
                linear-gradient(160deg, #0a0f1e 0%, #0d1224 100%);
            z-index: 0;
        }

        /* Grille perspective */
        .perspective-grid {
            position: absolute;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .perspective-grid::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: -20%;
            right: -20%;
            height: 60%;
            background-image:
                linear-gradient(rgba(124,58,237,0.10) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124,58,237,0.10) 1px, transparent 1px);
            background-size: 60px 60px;
            transform: perspective(400px) rotateX(55deg);
            transform-origin: bottom center;
            mask-image: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 85%);
            -webkit-mask-image: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 85%);
        }

        /* Orbes flottantes */
        .orb {
            position: absolute;
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
        }

        .orb-1 {
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(124,58,237,0.35) 0%, transparent 70%);
            top: 8%;
            right: -60px;
            animation: float 8s ease-in-out infinite;
        }

        .orb-2 {
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(99,102,241,0.25) 0%, transparent 70%);
            bottom: 20%;
            left: 10%;
            animation: float 10s ease-in-out infinite reverse;
        }

        .orb-3 {
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(167,139,250,0.30) 0%, transparent 70%);
            top: 40%;
            left: 55%;
            animation: float 6s ease-in-out infinite 1s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50%      { transform: translateY(-20px) scale(1.04); }
        }

        /* Contenu vitrine */
        .showcase > * { position: relative; z-index: 1; }

        .showcase-logo img {
            width: 150px;
            height: auto;
            filter: brightness(0) invert(1) opacity(0.85);
        }

        .showcase-body { max-width: 420px; }

        .showcase-body h1 {
            font-size: 40px;
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -1px;
            color: #ffffff;
            margin-bottom: 16px;
        }

        .showcase-body h1 .gradient-text {
            background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 50%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .showcase-body p {
            font-size: 15px;
            line-height: 1.7;
            color: rgba(255,255,255,0.50);
            margin-bottom: 40px;
        }

        /* Feature cards */
        .features { display: flex; flex-direction: column; gap: 12px; }

        .feature {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 18px;
            border-radius: 14px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            transition: all 0.3s;
        }

        .feature:hover {
            background: rgba(124,58,237,0.08);
            border-color: rgba(124,58,237,0.20);
            transform: translateX(4px);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-icon.purple  { background: rgba(124,58,237,0.15); color: #a78bfa; }
        .feature-icon.blue    { background: rgba(99,102,241,0.15); color: #818cf8; }
        .feature-icon.emerald { background: rgba(16,185,129,0.15); color: #6ee7b7; }

        .feature-label { font-weight: 600; font-size: 13px; color: #ffffff; margin-bottom: 2px; }
        .feature-desc  { font-size: 12px; color: rgba(255,255,255,0.45); }

        /* Stats bottom */
        .showcase-footer {
            display: flex;
            gap: 32px;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            display: block;
        }

        .stat-value .accent { color: #a78bfa; }
        .stat-label { font-size: 12px; color: rgba(255,255,255,0.35); font-weight: 600; letter-spacing: 0.5px; }

        /* ══════════════════════════════════════
           PANNEAU DROIT — Connexion
           ══════════════════════════════════════ */
        .auth-panel {
            width: 100%;
            max-width: 520px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 44px;
            position: relative;
            z-index: 1;
        }

        /* Ligne accent verticale gauche (desktop) */
        .auth-panel::before {
            content: '';
            position: absolute;
            left: 0;
            top: 15%;
            bottom: 15%;
            width: 1px;
            background: linear-gradient(to bottom, transparent, rgba(124,58,237,0.30), transparent);
            display: none;
        }

        .auth-inner {
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
        }

        .auth-logo-mobile {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        .auth-logo-mobile img {
            width: 140px;
            height: auto;
            filter: brightness(0) invert(1) opacity(0.90);
        }

        /* Status pill */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 20px;
            background: rgba(16,185,129,0.10);
            border: 1px solid rgba(16,185,129,0.20);
            font-size: 11px;
            font-weight: 600;
            color: #6ee7b7;
            letter-spacing: 0.3px;
            margin-bottom: 28px;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10b981;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(16,185,129,0.4); }
            50%      { opacity: 0.7; box-shadow: 0 0 0 6px rgba(16,185,129,0); }
        }

        .auth-inner h2 {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .auth-description {
            font-size: 14px;
            color: rgba(255,255,255,0.45);
            line-height: 1.65;
            margin-bottom: 36px;
        }

        /* Alertes */
        .alert {
            padding: 13px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: rgba(239,68,68,0.08);
            border-color: rgba(239,68,68,0.20);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(16,185,129,0.08);
            border-color: rgba(16,185,129,0.20);
            color: #6ee7b7;
        }

        /* Bouton Google */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            width: 100%;
            padding: 16px 24px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            color: #ffffff;
            background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);
            border: none;
            transition: all 0.25s cubic-bezier(.4,0,.2,1);
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(124,58,237,0.30), inset 0 1px 0 rgba(255,255,255,0.10);
            position: relative;
            overflow: hidden;
        }

        .btn-google::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.10) 50%, transparent 100%);
            transform: translateX(-100%);
            transition: transform 0.5s;
        }

        .btn-google:hover::before { transform: translateX(100%); }

        .btn-google:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(124,58,237,0.40), inset 0 1px 0 rgba(255,255,255,0.15);
        }

        .btn-google:active { transform: translateY(0); }

        .btn-google .g-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Séparateur */
        .separator {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 32px 0 28px;
        }

        .separator::before, .separator::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.08), transparent);
        }

        .separator span {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.20);
        }

        /* Chips info */
        .info-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 32px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 13px;
            border-radius: 9px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.45);
            transition: all 0.2s;
        }

        .chip:hover {
            border-color: rgba(124,58,237,0.25);
            color: rgba(255,255,255,0.60);
        }

        .chip svg { opacity: 0.5; flex-shrink: 0; }

        /* Footer */
        .auth-footer {
            font-size: 12px;
            color: rgba(255,255,255,0.25);
            line-height: 1.6;
        }

        .auth-footer strong {
            color: rgba(255,255,255,0.45);
            font-weight: 600;
        }

        /* ══════════════════════════════════════
           RESPONSIVE
           ══════════════════════════════════════ */
        @media (min-width: 1024px) {
            .showcase { display: flex; }
            .auth-logo-mobile { display: none; }
            .auth-panel::before { display: block; }
            .auth-panel { padding: 48px 60px; }
        }

        @media (max-width: 520px) {
            .auth-panel { padding: 32px 24px; }
            .auth-inner h2 { font-size: 24px; }
            .auth-logo-mobile img { width: 120px; }
        }

        /* ══════════════════════════════════════
           ANIMATIONS ENTRÉE
           ══════════════════════════════════════ */
        @keyframes slideRight {
            from { opacity: 0; transform: translateX(-24px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .showcase-logo    { animation: slideRight 0.6s cubic-bezier(.4,0,.2,1) 0.1s both; }
        .showcase-body    { animation: slideRight 0.6s cubic-bezier(.4,0,.2,1) 0.2s both; }
        .features         { animation: slideRight 0.6s cubic-bezier(.4,0,.2,1) 0.35s both; }
        .showcase-footer  { animation: slideRight 0.6s cubic-bezier(.4,0,.2,1) 0.5s both; }
        .auth-inner       { animation: fadeUp 0.5s cubic-bezier(.4,0,.2,1) 0.15s both; }
    </style>
</head>
<body>

<!-- ═══ PANNEAU GAUCHE ═══ -->
<aside class="showcase">
    <div class="perspective-grid"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="showcase-logo">
        <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
    </div>

    <div class="showcase-body">
        <h1>Pilotez votre<br><span class="gradient-text">infrastructure cPanel</span></h1>
        <p>Interface d'administration centralisée pour la gestion sécurisée de vos serveurs, comptes et services hébergés.</p>

        <div class="features">
            <div class="feature">
                <div class="feature-icon purple">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L3 7v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V7l-9-5z"/><path d="M9 12l2 2 4-4"/></svg>
                </div>
                <div>
                    <div class="feature-label">Authentification Google</div>
                    <div class="feature-desc">Connexion SSO via compte professionnel uniquement</div>
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon blue">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/><circle cx="12" cy="16" r="1"/></svg>
                </div>
                <div>
                    <div class="feature-label">Rotation automatique</div>
                    <div class="feature-desc">Mots de passe cPanel renouvelés toutes les 4 heures</div>
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon emerald">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <div>
                    <div class="feature-label">Audit complet</div>
                    <div class="feature-desc">Chaque session documentée et vérifiable</div>
                </div>
            </div>
        </div>
    </div>

    <div class="showcase-footer">
        <div>
            <span class="stat-value">256<span class="accent">-bit</span></span>
            <span class="stat-label">Chiffrement SSL</span>
        </div>
        <div>
            <span class="stat-value">4<span class="accent">h</span></span>
            <span class="stat-label">Cycle rotation</span>
        </div>
        <div>
            <span class="stat-value">100<span class="accent">%</span></span>
            <span class="stat-label">Actions loguées</span>
        </div>
    </div>
</aside>

<!-- ═══ PANNEAU DROIT ═══ -->
<main class="auth-panel">
    <div class="auth-inner">
        <div class="auth-logo-mobile">
            <img src="/images/logo-dark.svg" alt="Groupe Speed Cloud">
        </div>

        <div class="status-pill">
            <span class="status-dot"></span>
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

        <h2>Bienvenue</h2>
        <p class="auth-description">Identifiez-vous avec votre compte Google professionnel pour accéder au panel d'administration cPanel.</p>

        <a href="{{ route('auth.google') }}" class="btn-google">
            <span class="g-icon">
                <svg width="18" height="18" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
            </span>
            <span>Continuer avec Google</span>
        </a>

        <div class="separator"><span>Sécurité</span></div>

        <div class="info-chips">
            <span class="chip">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L3 7v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V7l-9-5z"/></svg>
                SSL / TLS
            </span>
            <span class="chip">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="7" r="4"/><path d="M5.5 21c0-4 3-7 6.5-7s6.5 3 6.5 7"/></svg>
                Accès sur invitation
            </span>
            <span class="chip">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Surveillance temps réel
            </span>
        </div>

        <p class="auth-footer">
            Seuls les comptes <strong>autorisés par un administrateur</strong> peuvent accéder à cette interface. Toutes les tentatives de connexion sont enregistrées.
        </p>
    </div>
</main>

</body>
</html>
