@extends('layouts.app')

@section('title', 'Accès cPanel')
@section('page-title', 'Accès cPanel')

@section('content')

<div class="page-header">
    <h1>Accès cPanel</h1>
</div>

@if(session('error'))
<div class="alert alert-danger mb-4">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body text-center py-5">
        <div class="mb-4">
            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5"
                 class="text-primary" style="color: var(--accent, #60a5fa);">
                <circle cx="32" cy="32" r="28"/>
                <path d="M20 32a12 12 0 1 1 24 0"/>
                <path d="M32 20v4M32 40v4M20 32h4M40 32h4"/>
                <circle cx="32" cy="32" r="4" fill="currentColor" stroke="none"/>
            </svg>
        </div>
        <h2 class="mb-2" style="font-size:1.25rem; font-weight:600;">
            Connexion automatique à cPanel
        </h2>
        <p class="mb-1" style="color: var(--text-muted, #94a3b8); font-size:.9rem;">
            Serveur&nbsp;: <strong>{{ $host ?: 'Non configuré' }}</strong>
        </p>
        <p class="mb-4" style="color: var(--text-muted, #94a3b8); font-size:.875rem;">
            Cliquez sur le bouton ci-dessous pour ouvrir cPanel sans saisir vos identifiants.
        </p>

        @if($host)
        <form method="POST" action="{{ route('cpanel.connect') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                     stroke-width="1.5" style="vertical-align:middle; margin-right:6px;">
                    <path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1v-3"/>
                    <polyline points="9,1 15,1 15,7"/>
                    <line x1="15" y1="1" x2="7" y2="9"/>
                </svg>
                Ouvrir cPanel
            </button>
        </form>

        <hr class="my-4" style="border-color: var(--border, #334155);">

        <p class="mb-2" style="color: var(--text-muted, #94a3b8); font-size:.85rem;">
            Connexion manuelle (si l'autologin échoue)
        </p>
        @if($publicKey)
        <form id="manual-login-form" method="POST" action="{{ route('cpanel.manual-login') }}"
              class="d-inline-flex align-items-center gap-2" style="flex-wrap:wrap; justify-content:center;">
            @csrf
            <input type="hidden" name="user" value="{{ $username }}">
            <input type="hidden" id="cpanel-encrypted-pass" name="encrypted_pass" value="">
            <label for="cpanel-pass" class="visually-hidden">Mot de passe</label>
            <input type="password" id="cpanel-pass"
                   class="form-control form-control-sm"
                   style="max-width:180px; display:inline-block;"
                   placeholder="Mot de passe cPanel" required>
            <button type="submit" class="btn btn-secondary btn-sm">
                Se connecter
            </button>
        </form>
        <script>
        (function () {
            var publicKeyPem = @json($publicKey);

            function pemToBuffer(pem) {
                var b64 = pem.replace(/-----[^-]+-----/g, '').replace(/\s+/g, '');
                var binary = atob(b64);
                var buf = new Uint8Array(binary.length);
                for (var i = 0; i < binary.length; i++) buf[i] = binary.charCodeAt(i);
                return buf.buffer;
            }

            document.getElementById('manual-login-form').addEventListener('submit', function (e) {
                e.preventDefault();
                var form = this;
                var passField = document.getElementById('cpanel-pass');
                var encField  = document.getElementById('cpanel-encrypted-pass');
                var password  = passField.value;
                if (!password) return;

                window.crypto.subtle.importKey(
                    'spki',
                    pemToBuffer(publicKeyPem),
                    { name: 'RSA-OAEP', hash: 'SHA-256' },
                    false,
                    ['encrypt']
                ).then(function (key) {
                    return window.crypto.subtle.encrypt(
                        { name: 'RSA-OAEP' },
                        key,
                        new TextEncoder().encode(password)
                    );
                }).then(function (encrypted) {
                    var bytes = new Uint8Array(encrypted);
                    var binary = '';
                    bytes.forEach(function (b) { binary += String.fromCharCode(b); });
                    encField.value = btoa(binary);
                    passField.value = '';
                    form.submit();
                }).catch(function (err) {
                    console.error('Encryption error:', err);
                    alert('Erreur de chiffrement. Votre navigateur ne supporte pas cette fonctionnalité.');
                });
            });
        })();
        </script>
        @else
        <div class="alert alert-warning d-inline-block" style="font-size:.85rem;">
            Impossible de générer la clé de chiffrement. Veuillez recharger la page.
        </div>
        @endif
        @else
        <div class="alert alert-warning d-inline-block">
            cPanel n'est pas configuré. Définissez <code>CPANEL_HOST</code>, <code>CPANEL_USERNAME</code>
            et <code>CPANEL_TOKEN</code> dans votre fichier <code>.env</code>.
        </div>
        @endif
    </div>
</div>

@endsection
