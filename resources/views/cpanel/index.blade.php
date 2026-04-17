@extends('layouts.app')

@section('title', 'Accès cPanel')
@section('page-title', 'Accès cPanel')

@section('content')

<div class="page-header">
    <h1>Accès cPanel</h1>
</div>

{{-- ── Connexion ─────────────────────────────────────────────────────────── --}}
@if($cpanelUrl)
<div class="card mb-4">
    <div class="card-title">Connexion</div>
    <p class="text-muted" style="font-size:.875rem;margin-bottom:16px;">
        Se connecter à l'interface cPanel avec les identifiants configurés. Le mot de passe est automatiquement changé après chaque connexion.
    </p>
    <form action="{{ route('cpanel.manual-login') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                 stroke-width="1.5" style="vertical-align:middle;margin-right:6px;">
                <path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1v-3"/>
                <polyline points="9,1 15,1 15,7"/>
                <line x1="15" y1="1" x2="7" y2="9"/>
            </svg>
            Se connecter à cPanel
        </button>
    </form>
</div>
@else
<div class="alert alert-warning mb-4">
    cPanel n'est pas configuré. Définissez <code>CPANEL_HOST</code> dans votre fichier <code>.env</code>.
</div>
@endif

{{-- ── Informations serveur ──────────────────────────────────────────────── --}}
<div class="card mb-4">
    <div class="card-title">Informations serveur</div>
    <div class="table-wrap">
        <table style="width:100%;border-collapse:collapse;">
            <tr style="border-top:1px solid var(--border);">
                <td class="text-muted" style="width:180px;padding:12px 10px;vertical-align:middle;font-size:.875rem;">
                    <span style="display:inline-flex;align-items:center;gap:8px;">
                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.5;"><rect x="1" y="4" width="14" height="8" rx="1.5"/><circle cx="4" cy="8" r="1" fill="currentColor" stroke="none"/><line x1="7" y1="6" x2="7" y2="10"/></svg>
                        Hôte
                    </span>
                </td>
                <td style="padding:12px 10px;vertical-align:middle;">
                    <span style="font-size:.875rem;font-family:'Courier New',monospace;">{{ $host ?: '—' }}</span>
                </td>
            </tr>
            <tr style="border-top:1px solid var(--border);">
                <td class="text-muted" style="width:180px;padding:12px 10px;vertical-align:middle;font-size:.875rem;">
                    <span style="display:inline-flex;align-items:center;gap:8px;">
                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.5;"><circle cx="8" cy="8" r="6.5"/><path d="M8 4v4l3 2"/></svg>
                        Port
                    </span>
                </td>
                <td style="padding:12px 10px;vertical-align:middle;">
                    <span style="font-size:.875rem;font-family:'Courier New',monospace;">{{ $port ?: '—' }}</span>
                </td>
            </tr>
            <tr style="border-top:1px solid var(--border);">
                <td class="text-muted" style="width:180px;padding:12px 10px;vertical-align:middle;font-size:.875rem;">
                    <span style="display:inline-flex;align-items:center;gap:8px;">
                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.5;"><circle cx="8" cy="5" r="3"/><path d="M2 14c0-3 2.7-5 6-5s6 2 6 5"/></svg>
                        Utilisateur
                    </span>
                </td>
                <td style="padding:12px 10px;vertical-align:middle;">
                    <div style="display:inline-flex;align-items:center;gap:8px;">
                        <span id="val-username" style="font-size:.875rem;font-family:'Courier New',monospace;">{{ $username ?: '—' }}</span>
                        @if($username)
                        <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $username }}" title="Copier" style="padding:2px 6px;">
                            <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            <tr style="border-top:1px solid var(--border);">
                <td class="text-muted" style="width:180px;padding:12px 10px;vertical-align:middle;font-size:.875rem;">
                    <span style="display:inline-flex;align-items:center;gap:8px;">
                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.5;"><circle cx="8" cy="8" r="6.5"/><ellipse cx="8" cy="8" rx="3" ry="6.5"/><line x1="1.5" y1="8" x2="14.5" y2="8"/></svg>
                        Domaine
                    </span>
                </td>
                <td style="padding:12px 10px;vertical-align:middle;">
                    <div style="display:inline-flex;align-items:center;gap:8px;">
                        <span id="val-domain" style="font-size:.875rem;font-family:'Courier New',monospace;">{{ $domain ?: '—' }}</span>
                        @if($domain)
                        <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $domain }}" title="Copier" style="padding:2px 6px;">
                            <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            <tr style="border-top:1px solid var(--border);">
                <td class="text-muted" style="width:180px;padding:12px 10px;vertical-align:middle;font-size:.875rem;">
                    <span style="display:inline-flex;align-items:center;gap:8px;">
                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.5;"><rect x="3" y="7" width="10" height="7" rx="1.5"/><path d="M5 7V5a3 3 0 116 0v2"/></svg>
                        Mot de passe
                    </span>
                </td>
                <td style="padding:12px 10px;vertical-align:middle;">
                    @if($password)
                    <div style="display:inline-flex;align-items:center;gap:8px;">
                        <code id="val-password" style="font-size:.875rem;background:rgba(37,99,235,0.08);padding:4px 10px;border-radius:6px;letter-spacing:.3px;">●●●●●●●●●●●●</code>
                        <button type="button" class="btn btn-ghost btn-sm" id="toggle-password" title="Afficher / Masquer" style="padding:2px 6px;">
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="icon-eye"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><circle cx="8" cy="8" r="2.5"/></svg>
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="icon-eye-off" style="display:none;"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><circle cx="8" cy="8" r="2.5"/><line x1="2" y1="2" x2="14" y2="14" stroke-width="1.5"/></svg>
                        </button>
                        <button type="button" class="btn btn-ghost btn-sm copy-btn" id="copy-password" title="Copier le mot de passe" style="padding:2px 6px;">
                            <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
                        </button>
                    </div>
                    @else
                        <span class="text-muted" style="font-size:.875rem;font-style:italic;">Non configuré</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>

{{-- ── Sécurité (super-admin uniquement) ─────────────────────────────────── --}}
@if(auth()->user()?->isSuperAdmin())
<div class="card mb-4">
    <div class="card-title">Sécurité</div>
    <p class="text-muted" style="font-size:.875rem;margin-bottom:16px;">
        Le mot de passe cPanel est automatiquement changé après chaque connexion et chaque jour à 03h00.
        Vous pouvez également forcer une rotation immédiate.
    </p>
    <form action="{{ route('cpanel.rotate-password') }}" method="POST"
          onsubmit="return confirm('Êtes-vous sûr de vouloir changer le mot de passe cPanel immédiatement ?\n\nLe mot de passe actuel deviendra invalide.');"
          style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-warning">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                 stroke-width="1.5" style="vertical-align:middle;margin-right:6px;">
                <path d="M1.5 8a6.5 6.5 0 0112.48-2.5M14.5 8a6.5 6.5 0 01-12.48 2.5"/>
                <polyline points="14,2 14,5.5 10.5,5.5"/>
                <polyline points="2,14 2,10.5 5.5,10.5"/>
            </svg>
            Forcer la rotation du mot de passe
        </button>
    </form>
</div>
@endif

<div class="alert alert-warning" style="display:flex;align-items:center;gap:10px;">
    <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;color:var(--warning);">
        <path d="M8 1.5L1 13.5h14L8 1.5z"/><line x1="8" y1="6" x2="8" y2="9.5"/><circle cx="8" cy="11.5" r=".5" fill="currentColor" stroke="none"/>
    </svg>
    <span><strong>Page réservée aux techniciens autorisés</strong> — Le mot de passe est changé automatiquement après chaque utilisation.</span>
</div>

<script>
(function () {
    'use strict';

    var _pw = @json($password);

    // ── Password toggle ─────────────────────────────────────────────────────
    var pwEl      = document.getElementById('val-password');
    var toggleBtn = document.getElementById('toggle-password');
    if (pwEl && toggleBtn && _pw) {
        var visible = false;
        var eyeOn  = toggleBtn.querySelector('.icon-eye');
        var eyeOff = toggleBtn.querySelector('.icon-eye-off');

        toggleBtn.addEventListener('click', function () {
            visible = !visible;
            pwEl.textContent = visible ? _pw : '●●●●●●●●●●●●';
            eyeOn.style.display  = visible ? 'none' : '';
            eyeOff.style.display = visible ? '' : 'none';
        });
    }

    // ── Copy password ───────────────────────────────────────────────────────
    var copyPwBtn = document.getElementById('copy-password');
    if (copyPwBtn && _pw) {
        copyPwBtn.addEventListener('click', function () {
            navigator.clipboard.writeText(_pw).then(function () {
                showCopyFeedback(copyPwBtn);
            });
        });
    }

    // ── Generic copy buttons ────────────────────────────────────────────────
    document.querySelectorAll('.copy-btn:not(#copy-password)').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var text = btn.getAttribute('data-copy');
            if (text) {
                navigator.clipboard.writeText(text).then(function () {
                    showCopyFeedback(btn);
                });
            }
        });
    });

    function showCopyFeedback(btn) {
        var original = btn.innerHTML;
        btn.innerHTML = '<svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="2,8 6,13 14,3"/></svg>';
        btn.style.color = 'var(--success, #22c55e)';
        setTimeout(function () {
            btn.innerHTML = original;
            btn.style.color = '';
        }, 1200);
    }
}());
</script>

@endsection
