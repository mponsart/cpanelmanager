@extends('layouts.app')

@section('title', 'Accès cPanel')
@section('page-title', 'Accès cPanel')

@section('content')

<div class="page-header">
    <h1>Accès cPanel</h1>
    @if($cpanelUrl)
    <form action="{{ route('cpanel.manual-login') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="vertical-align:middle;margin-right:6px;"><path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1v-3"/><polyline points="9,1 15,1 15,7"/><line x1="15" y1="1" x2="7" y2="9"/></svg>
            Se connecter à cPanel
        </button>
    </form>
    @endif
</div>

@if(!$cpanelUrl)
<div class="alert alert-warning">
    cPanel n'est pas configuré. Définissez <code>CPANEL_HOST</code> dans votre fichier <code>.env</code>.
</div>
@endif

{{-- ── Informations serveur ──────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-title">Informations serveur</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:180px;">Paramètre</th>
                    <th>Valeur</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-muted">Hôte</td>
                    <td>
                        <div class="flex gap-2">
                            <code>{{ $host ?: '—' }}</code>
                            @if($host)
                            <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $host }}" title="Copier">
                                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Port</td>
                    <td><code>{{ $port ?: '—' }}</code></td>
                </tr>
                <tr>
                    <td class="text-muted">Utilisateur</td>
                    <td>
                        <div class="flex gap-2">
                            <code>{{ $username ?: '—' }}</code>
                            @if($username)
                            <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $username }}" title="Copier">
                                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Domaine</td>
                    <td>
                        <div class="flex gap-2">
                            <code>{{ $domain ?: '—' }}</code>
                            @if($domain)
                            <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $domain }}" title="Copier">
                                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @if($isSuperAdmin)
                <tr>
                    <td class="text-muted">Mot de passe</td>
                    <td>
                        @if($password)
                        <div class="flex gap-2">
                            <code id="val-password" style="max-width:360px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">●●●●●●●●●●●●</code>
                            <button type="button" class="btn btn-ghost btn-sm" id="toggle-password" title="Afficher / Masquer">
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="icon-eye"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><circle cx="8" cy="8" r="2.5"/></svg>
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="icon-eye-off" style="display:none;"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><circle cx="8" cy="8" r="2.5"/><line x1="2" y1="2" x2="14" y2="14" stroke-width="1.5"/></svg>
                            </button>
                            <button type="button" class="btn btn-ghost btn-sm copy-btn" id="copy-password" title="Copier le mot de passe">
                                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
                            </button>
                        </div>
                        @else
                            <span class="text-muted" style="font-style:italic;">Non configuré</span>
                        @endif
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- ── Sécurité (super-admin uniquement) ─────────────────────────────────── --}}
@if($isSuperAdmin)
<div class="card">
    <div class="card-title">Sécurité</div>
    <p class="text-muted" style="font-size:.875rem;margin-bottom:16px;">
        Le mot de passe est automatiquement roté après chaque connexion et chaque jour à 03h00.
    </p>
    <form action="{{ route('cpanel.rotate-password') }}" method="POST"
          onsubmit="return confirm('Êtes-vous sûr de vouloir changer le mot de passe cPanel immédiatement ?\n\nLe mot de passe actuel deviendra invalide.');">
        @csrf
        <button type="submit" class="btn btn-warning">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="vertical-align:middle;margin-right:6px;"><path d="M1.5 8a6.5 6.5 0 0112.48-2.5M14.5 8a6.5 6.5 0 01-12.48 2.5"/><polyline points="14,2 14,5.5 10.5,5.5"/><polyline points="2,14 2,10.5 5.5,10.5"/></svg>
            Forcer la rotation du mot de passe
        </button>
    </form>
</div>
@endif

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
