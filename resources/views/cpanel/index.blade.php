@extends('layouts.app')

@section('title', 'Accès cPanel')
@section('page-title', 'Accès cPanel')

@section('content')

<div class="page-header">
    <h1>Accès cPanel</h1>
    @if($cpanelUrl)
    <button type="button" class="btn btn-primary" id="open-login-modal">
        <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1v-3"/><polyline points="9,1 15,1 15,7"/><line x1="15" y1="1" x2="7" y2="9"/></svg>
        Se connecter à cPanel
    </button>
    <form action="{{ route('cpanel.manual-login') }}" method="POST" id="login-form" style="display:none;">
        @csrf
    </form>
    @endif
</div>

{{-- ── Bannière d'avertissement permanente ────────────────────────────────── --}}
<div class="alert alert-warning" style="margin-bottom:22px;">
    <strong>Rappel :</strong> l'accès direct à cPanel doit rester exceptionnel. Utilisez-le uniquement pour les fonctionnalités qui ne sont pas encore disponibles dans ce panneau d'administration.
</div>

@if(!$cpanelUrl)
<div class="alert alert-error">
    cPanel n'est pas configuré. Définissez <code class="code">CPANEL_HOST</code> dans votre fichier <code class="code">.env</code>.
</div>
@endif

{{-- ── Informations serveur ───────────────────────────────────────────────── --}}
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));">
    <div class="stat-card">
        <div class="stat-label">Hôte</div>
        <div style="display:flex;align-items:center;gap:8px;padding-left:14px;margin-top:6px;">
            <span style="font-size:.9rem;font-weight:600;color:var(--text);font-family:ui-monospace,monospace;">{{ $host ?: '—' }}</span>
            @if($host)
            <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $host }}" title="Copier" style="padding:2px 6px;">
                <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
            </button>
            @endif
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Port</div>
        <div style="padding-left:14px;margin-top:6px;">
            <span style="font-size:.9rem;font-weight:600;color:var(--text);font-family:ui-monospace,monospace;">{{ $port ?: '—' }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Utilisateur</div>
        <div style="display:flex;align-items:center;gap:8px;padding-left:14px;margin-top:6px;">
            <span style="font-size:.9rem;font-weight:600;color:var(--text);font-family:ui-monospace,monospace;">{{ $username ?: '—' }}</span>
            @if($username)
            <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $username }}" title="Copier" style="padding:2px 6px;">
                <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
            </button>
            @endif
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Domaine</div>
        <div style="display:flex;align-items:center;gap:8px;padding-left:14px;margin-top:6px;">
            <span style="font-size:.9rem;font-weight:600;color:var(--text);font-family:ui-monospace,monospace;">{{ $domain ?: '—' }}</span>
            @if($domain)
            <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $domain }}" title="Copier" style="padding:2px 6px;">
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
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="7" width="10" height="7" rx="1.5"/><path d="M5 7V5a3 3 0 116 0v2"/></svg>
            Mot de passe cPanel
        </div>
        @if($password)
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <code id="val-password" style="font-size:.875rem;padding:8px 14px;background:var(--panel-soft);border:1px solid var(--border);border-radius:8px;letter-spacing:.5px;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-family:ui-monospace,monospace;">●●●●●●●●●●●●●●●●</code>
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
        <p class="text-muted" style="font-style:italic;">Mot de passe non configuré dans le fichier <code class="code">.env</code>.</p>
        @endif
    </div>

    {{-- Sécurité --}}
    <div class="card">
        <div class="card-title">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 1l6 3v4c0 3.5-2.5 6.5-6 7.5C4.5 14.5 2 11.5 2 8V4l6-3z"/></svg>
            Sécurité
        </div>
        <p class="text-muted" style="font-size:.85rem;margin-bottom:14px;">
            Le mot de passe est roté automatiquement après chaque connexion et quotidiennement à 03h00.
        </p>
        <form id="rotate-form" action="{{ route('cpanel.rotate-password') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning" id="rotate-btn">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="rotate-icon"><path d="M1.5 8a6.5 6.5 0 0112.48-2.5M14.5 8a6.5 6.5 0 01-12.48 2.5"/><polyline points="14,2 14,5.5 10.5,5.5"/><polyline points="2,14 2,10.5 5.5,10.5"/></svg>
                Forcer la rotation
            </button>
        </form>
    </div>
</div>
@endif

{{-- ── Modal d'avertissement avant connexion ─────────────────────────────── --}}
<div id="login-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,0.50);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);justify-content:center;align-items:center;">
    <div style="background:var(--panel);border:1px solid var(--border);border-radius:14px;padding:0;box-shadow:0 12px 40px rgba(15,23,42,0.20);max-width:480px;width:92%;overflow:hidden;">
        {{-- Header rouge/warning --}}
        <div style="background:linear-gradient(135deg,#fef3c7,#fde68a);padding:20px 24px;border-bottom:1px solid #fde68a;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:42px;height:42px;border-radius:11px;background:#fffbeb;border:1px solid #fcd34d;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 16 16" fill="none" stroke="#d97706" stroke-width="1.5"><path d="M8 1l6 3v4c0 3.5-2.5 6.5-6 7.5C4.5 14.5 2 11.5 2 8V4l6-3z"/><line x1="8" y1="5.5" x2="8" y2="8.5"/><circle cx="8" cy="10.5" r="0.5" fill="#d97706" stroke="none"/></svg>
                </div>
                <div>
                    <div style="font-size:16px;font-weight:700;color:#92400e;">Accès limité</div>
                    <div style="font-size:12px;color:#a16207;margin-top:2px;">Connexion directe à cPanel</div>
                </div>
            </div>
        </div>
        {{-- Body --}}
        <div style="padding:24px;">
            <p style="font-size:14px;color:var(--text);line-height:1.65;margin-bottom:16px;">
                Ces identifiants donnent accès à l'ensemble de cPanel. Merci de respecter ces règles :
            </p>
            <ul style="font-size:13px;color:var(--text-muted);line-height:1.7;margin:0 0 20px 0;padding-left:18px;">
                <li style="margin-bottom:6px;">Utiliser <strong style="color:var(--text);">uniquement</strong> pour les fonctionnalités non disponibles dans ce panneau</li>
                <li style="margin-bottom:6px;">Limiter la durée de la session au strict nécessaire</li>
                <li style="margin-bottom:6px;">Ne jamais partager ou enregistrer les identifiants</li>
                <li>Toute action est journalisée et auditée</li>
            </ul>
            <label style="display:flex;align-items:flex-start;gap:8px;cursor:pointer;font-size:13px;color:var(--text);font-weight:500;margin-bottom:0;">
                <input type="checkbox" id="modal-accept" style="margin-top:3px;width:16px;height:16px;accent-color:var(--accent);flex-shrink:0;">
                J'ai lu et j'accepte ces conditions d'utilisation
            </label>
        </div>
        {{-- Footer --}}
        <div style="padding:16px 24px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:flex-end;gap:10px;background:var(--panel-soft);">
            <button type="button" class="btn btn-ghost" id="modal-cancel">Annuler</button>
            <button type="button" class="btn btn-primary" id="modal-confirm" disabled style="opacity:0.5;">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1v-3"/><polyline points="9,1 15,1 15,7"/><line x1="15" y1="1" x2="7" y2="9"/></svg>
                Continuer vers cPanel
            </button>
        </div>
    </div>
</div>

{{-- ── Overlay de chargement ─────────────────────────────────────────────── --}}
<div id="action-overlay" style="display:none;position:fixed;inset:0;z-index:10000;background:rgba(15,23,42,0.50);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);justify-content:center;align-items:center;">
    <div style="background:var(--panel);border:1px solid var(--border);border-radius:14px;padding:36px 44px;text-align:center;box-shadow:0 12px 40px rgba(15,23,42,0.20);max-width:360px;">
        <svg width="36" height="36" viewBox="0 0 16 16" fill="none" stroke="var(--accent)" stroke-width="1.5" style="animation:spin 1s linear infinite;margin-bottom:14px;"><path d="M1.5 8a6.5 6.5 0 0112.48-2.5M14.5 8a6.5 6.5 0 01-12.48 2.5"/><polyline points="14,2 14,5.5 10.5,5.5"/><polyline points="2,14 2,10.5 5.5,10.5"/></svg>
        <p id="overlay-title" style="font-weight:700;font-size:1rem;margin:0 0 6px;color:var(--text);">Chargement…</p>
        <p id="overlay-desc" style="font-size:.85rem;color:var(--text-muted);margin:0;">Veuillez patienter.</p>
    </div>
</div>
<style>@keyframes spin{to{transform:rotate(360deg)}}</style>

<script>
(function () {
    'use strict';

    var overlay      = document.getElementById('action-overlay');
    var overlayTitle = document.getElementById('overlay-title');
    var overlayDesc  = document.getElementById('overlay-desc');

    function showOverlay(title, desc) {
        if (!overlay) return;
        overlayTitle.textContent = title;
        overlayDesc.textContent  = desc;
        overlay.style.display    = 'flex';
    }

    // ── Modal d'avertissement ────────────────────────────────────────────────
    var loginModal   = document.getElementById('login-modal');
    var openBtn      = document.getElementById('open-login-modal');
    var cancelBtn    = document.getElementById('modal-cancel');
    var confirmBtn   = document.getElementById('modal-confirm');
    var acceptCb     = document.getElementById('modal-accept');
    var loginForm    = document.getElementById('login-form');

    if (openBtn && loginModal) {
        openBtn.addEventListener('click', function () {
            loginModal.style.display = 'flex';
            if (acceptCb) { acceptCb.checked = false; }
            if (confirmBtn) { confirmBtn.disabled = true; confirmBtn.style.opacity = '0.5'; }
        });

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                loginModal.style.display = 'none';
            });
        }

        loginModal.addEventListener('click', function (e) {
            if (e.target === loginModal) loginModal.style.display = 'none';
        });

        if (acceptCb && confirmBtn) {
            acceptCb.addEventListener('change', function () {
                confirmBtn.disabled = !acceptCb.checked;
                confirmBtn.style.opacity = acceptCb.checked ? '1' : '0.5';
            });
        }

        if (confirmBtn && loginForm) {
            confirmBtn.addEventListener('click', function () {
                loginModal.style.display = 'none';
                showOverlay('Connexion en cours…', 'Redirection vers cPanel, veuillez patienter.');
                loginForm.submit();
            });
        }
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
        btn.style.color = 'var(--success)';
        setTimeout(function () { btn.innerHTML = original; btn.style.color = ''; }, 1200);
    }
}());
</script>

@endsection
