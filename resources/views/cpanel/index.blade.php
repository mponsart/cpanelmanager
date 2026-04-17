@extends('layouts.app')

@section('title', 'Accès cPanel')
@section('page-title', 'Accès cPanel')

@section('content')

<div class="page-header">
    <h1>Accès cPanel</h1>
    @if($cpanelUrl)
    <form action="{{ route('cpanel.manual-login') }}" method="POST" id="login-form">
        @csrf
        <button type="submit" class="btn btn-primary" id="login-btn">
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

{{-- ── Informations serveur (stat-cards) ─────────────────────────────────── --}}
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));">
    <div class="stat-card" style="border-left:3px solid var(--accent);">
        <div class="stat-label">Hôte</div>
        <div style="display:flex;align-items:center;gap:8px;padding-left:12px;margin-top:6px;">
            <span style="font-size:.9rem;font-weight:600;color:var(--text);font-family:'Courier New',monospace;">{{ $host ?: '—' }}</span>
            @if($host)
            <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $host }}" title="Copier" style="padding:2px 4px;">
                <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
            </button>
            @endif
        </div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--success);">
        <div class="stat-label">Port</div>
        <div style="padding-left:12px;margin-top:6px;">
            <span style="font-size:.9rem;font-weight:600;color:var(--text);font-family:'Courier New',monospace;">{{ $port ?: '—' }}</span>
        </div>
    </div>
    <div class="stat-card" style="border-left:3px solid #a78bfa;">
        <div class="stat-label">Utilisateur</div>
        <div style="display:flex;align-items:center;gap:8px;padding-left:12px;margin-top:6px;">
            <span style="font-size:.9rem;font-weight:600;color:var(--text);font-family:'Courier New',monospace;">{{ $username ?: '—' }}</span>
            @if($username)
            <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $username }}" title="Copier" style="padding:2px 4px;">
                <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
            </button>
            @endif
        </div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--warning);">
        <div class="stat-label">Domaine</div>
        <div style="display:flex;align-items:center;gap:8px;padding-left:12px;margin-top:6px;">
            <span style="font-size:.9rem;font-weight:600;color:var(--text);font-family:'Courier New',monospace;">{{ $domain ?: '—' }}</span>
            @if($domain)
            <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $domain }}" title="Copier" style="padding:2px 4px;">
                <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
            </button>
            @endif
        </div>
    </div>
</div>

{{-- ── Super-admin : mot de passe + sécurité ─────────────────────────────── --}}
@if($isSuperAdmin)
<div class="form-row form-row-2">
    {{-- Mot de passe --}}
    <div class="card">
        <div class="card-title">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-right:8px;opacity:.6;"><rect x="3" y="7" width="10" height="7" rx="1.5"/><path d="M5 7V5a3 3 0 116 0v2"/></svg>
            Mot de passe cPanel
        </div>
        @if($password)
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <code id="val-password" style="font-size:.875rem;padding:6px 12px;background:var(--bg);border:1px solid var(--border);border-radius:6px;letter-spacing:.5px;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">●●●●●●●●●●●●●●●●</code>
            <button type="button" class="btn btn-ghost btn-sm" id="toggle-password" title="Afficher / Masquer">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="icon-eye"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><circle cx="8" cy="8" r="2.5"/></svg>
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="icon-eye-off" style="display:none;"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><circle cx="8" cy="8" r="2.5"/><line x1="2" y1="2" x2="14" y2="14" stroke-width="1.5"/></svg>
            </button>
            <button type="button" class="btn btn-ghost btn-sm copy-btn" id="copy-password" title="Copier le mot de passe">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
            </button>
        </div>
        <p class="text-muted" style="font-size:.8rem;margin-top:10px;">Visible uniquement par les super-administrateurs.</p>
        @else
        <p class="text-muted" style="font-style:italic;">Mot de passe non configuré dans le fichier <code>.env</code>.</p>
        @endif
    </div>

    {{-- Sécurité --}}
    <div class="card">
        <div class="card-title">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-right:8px;opacity:.6;"><path d="M8 1l6 3v4c0 3.5-2.5 6.5-6 7.5C4.5 14.5 2 11.5 2 8V4l6-3z"/></svg>
            Sécurité
        </div>
        <p class="text-muted" style="font-size:.85rem;margin-bottom:14px;">
            Le mot de passe est roté automatiquement après chaque connexion et quotidiennement à 03h00.
        </p>
        <form id="rotate-form" action="{{ route('cpanel.rotate-password') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning" id="rotate-btn">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="vertical-align:middle;margin-right:6px;" class="rotate-icon"><path d="M1.5 8a6.5 6.5 0 0112.48-2.5M14.5 8a6.5 6.5 0 01-12.48 2.5"/><polyline points="14,2 14,5.5 10.5,5.5"/><polyline points="2,14 2,10.5 5.5,10.5"/></svg>
                Forcer la rotation
            </button>
        </form>
    </div>
</div>
@endif

{{-- ── Overlay de chargement ─────────────────────────────────────────────── --}}
<div id="action-overlay" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.45);backdrop-filter:blur(2px);justify-content:center;align-items:center;">
    <div style="background:var(--panel);border:1px solid var(--border);border-radius:var(--radius);padding:32px 40px;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,.25);max-width:360px;">
        <svg width="36" height="36" viewBox="0 0 16 16" fill="none" stroke="var(--accent, #8a4dfd)" stroke-width="1.5" style="animation:spin 1s linear infinite;margin-bottom:14px;"><path d="M1.5 8a6.5 6.5 0 0112.48-2.5M14.5 8a6.5 6.5 0 01-12.48 2.5"/><polyline points="14,2 14,5.5 10.5,5.5"/><polyline points="2,14 2,10.5 5.5,10.5"/></svg>
        <p id="overlay-title" style="font-weight:600;font-size:1rem;margin:0 0 6px;color:var(--text);">Chargement…</p>
        <p id="overlay-desc" style="font-size:.85rem;color:var(--text-muted);margin:0;">Veuillez patienter.</p>
    </div>
</div>
<style>@keyframes spin{to{transform:rotate(360deg)}}</style>

<script>
(function () {
    'use strict';

    var overlay     = document.getElementById('action-overlay');
    var overlayTitle = document.getElementById('overlay-title');
    var overlayDesc  = document.getElementById('overlay-desc');

    function showOverlay(title, desc) {
        if (!overlay) return;
        overlayTitle.textContent = title;
        overlayDesc.textContent  = desc;
        overlay.style.display    = 'flex';
    }

    // ── Connexion overlay ───────────────────────────────────────────────────
    var loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function () {
            document.getElementById('login-btn').disabled = true;
            showOverlay('Connexion en cours…', 'Redirection vers cPanel, veuillez patienter.');
        });
    }

    // ── Rotation overlay ────────────────────────────────────────────────────
    var rotateForm = document.getElementById('rotate-form');
    if (rotateForm) {
        rotateForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!confirm('Êtes-vous sûr de vouloir changer le mot de passe cPanel immédiatement ?\n\nLe mot de passe actuel deviendra invalide.')) return;
            document.getElementById('rotate-btn').disabled = true;
            showOverlay('Rotation en cours…', 'Le mot de passe cPanel est en cours de changement.');
            rotateForm.submit();
        });
    }

    // ── Password toggle ─────────────────────────────────────────────────────
    var _pw       = @json($password);
    var pwEl      = document.getElementById('val-password');
    var toggleBtn = document.getElementById('toggle-password');
    if (pwEl && toggleBtn && _pw) {
        var visible = false;
        var eyeOn  = toggleBtn.querySelector('.icon-eye');
        var eyeOff = toggleBtn.querySelector('.icon-eye-off');
        toggleBtn.addEventListener('click', function () {
            visible = !visible;
            pwEl.textContent = visible ? _pw : '●●●●●●●●●●●●●●●●';
            eyeOn.style.display  = visible ? 'none' : '';
            eyeOff.style.display = visible ? '' : 'none';
        });
    }

    // ── Copy password ───────────────────────────────────────────────────────
    var copyPwBtn = document.getElementById('copy-password');
    if (copyPwBtn && _pw) {
        copyPwBtn.addEventListener('click', function () {
            navigator.clipboard.writeText(_pw).then(function () { showCopyFeedback(copyPwBtn); });
        });
    }

    // ── Generic copy buttons ────────────────────────────────────────────────
    document.querySelectorAll('.copy-btn:not(#copy-password)').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var text = btn.getAttribute('data-copy');
            if (text) {
                navigator.clipboard.writeText(text).then(function () { showCopyFeedback(btn); });
            }
        });
    });

    function showCopyFeedback(btn) {
        var original = btn.innerHTML;
        btn.innerHTML = '<svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="2,8 6,13 14,3"/></svg>';
        btn.style.color = 'var(--success, #22c55e)';
        setTimeout(function () { btn.innerHTML = original; btn.style.color = ''; }, 1200);
    }
}());
</script>

@endsection
