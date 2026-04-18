@extends('layouts.app')

@section('title', 'Accès cPanel')
@section('page-title', 'Accès cPanel')

@section('content')

{{-- ── Bannière d'avertissement ───────────────────────────────────────────── --}}
<div class="alert alert-warning" style="margin-bottom:20px;">
    <strong>Rappel :</strong> l'accès direct à cPanel doit rester exceptionnel. Utilisez-le uniquement pour les fonctionnalités non disponibles dans ce panneau.
</div>

@if(!$cpanelUrl)
<div class="alert alert-error">
    cPanel n'est pas configuré. Définissez <code class="code">CPANEL_HOST</code> dans votre fichier <code class="code">.env</code>.
</div>
@endif

{{-- ── Carte principale : Connexion ──────────────────────────────────────── --}}
<div class="card" style="margin-bottom:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border);">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:36px;height:36px;border-radius:8px;background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.16);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="var(--accent)" stroke-width="1.5"><rect x="1" y="2" width="14" height="5" rx="1"/><rect x="1" y="9" width="14" height="5" rx="1"/><circle cx="12.5" cy="4.5" r="1" fill="var(--accent)" stroke="none"/><circle cx="12.5" cy="11.5" r="1" fill="var(--accent)" stroke="none"/></svg>
            </div>
            <div>
                <div style="font-size:15px;font-weight:700;color:var(--text);">Serveur cPanel</div>
                <div style="font-size:12px;color:var(--text-muted);">Informations de connexion</div>
            </div>
        </div>
        @if($cpanelUrl)
        <button type="button" class="btn btn-primary" id="open-login-modal">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1v-3"/><polyline points="9,1 15,1 15,7"/><line x1="15" y1="1" x2="7" y2="9"/></svg>
            Se connecter
        </button>
        @endif
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:0;border:1px solid var(--border);border-radius:8px;overflow:hidden;">
        <div style="padding:14px 16px;border-right:1px solid var(--border);border-bottom:1px solid var(--border);">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);margin-bottom:4px;">Hôte</div>
            <div style="display:flex;align-items:center;gap:6px;">
                <span style="font-size:13px;font-weight:600;color:var(--text);font-family:ui-monospace,monospace;">{{ $host ?: '—' }}</span>
                @if($host)
                <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $host }}" title="Copier" style="padding:1px 4px;border:none;background:none;color:var(--text-muted);">
                    <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
                </button>
                @endif
            </div>
        </div>
        <div style="padding:14px 16px;border-right:1px solid var(--border);border-bottom:1px solid var(--border);">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);margin-bottom:4px;">Port</div>
            <span style="font-size:13px;font-weight:600;color:var(--text);font-family:ui-monospace,monospace;">{{ $port ?: '—' }}</span>
        </div>
        <div style="padding:14px 16px;border-right:1px solid var(--border);border-bottom:1px solid var(--border);">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);margin-bottom:4px;">Utilisateur</div>
            <div style="display:flex;align-items:center;gap:6px;">
                <span style="font-size:13px;font-weight:600;color:var(--text);font-family:ui-monospace,monospace;">{{ $username ?: '—' }}</span>
                @if($username)
                <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $username }}" title="Copier" style="padding:1px 4px;border:none;background:none;color:var(--text-muted);">
                    <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
                </button>
                @endif
            </div>
        </div>
        <div style="padding:14px 16px;border-bottom:1px solid var(--border);">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);margin-bottom:4px;">Domaine</div>
            <div style="display:flex;align-items:center;gap:6px;">
                <span style="font-size:13px;font-weight:600;color:var(--text);font-family:ui-monospace,monospace;">{{ $domain ?: '—' }}</span>
                @if($domain)
                <button type="button" class="btn btn-ghost btn-sm copy-btn" data-copy="{{ $domain }}" title="Copier" style="padding:1px 4px;border:none;background:none;color:var(--text-muted);">
                    <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── Bandeau session active (masqué par défaut) ─────────────────────────── --}}
<div id="session-panel" class="card" style="display:none;margin-bottom:20px;border:1px solid rgba(124,58,237,0.25);background:linear-gradient(135deg,rgba(124,58,237,0.04),rgba(124,58,237,0.08));">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="width:42px;height:42px;border-radius:10px;background:rgba(124,58,237,0.12);border:1px solid rgba(124,58,237,0.20);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 16 16" fill="none" stroke="var(--accent)" stroke-width="1.5"><circle cx="8" cy="8" r="6.5"/><polyline points="8,4.5 8,8 11,9.5"/></svg>
            </div>
            <div>
                <div style="font-size:14px;font-weight:700;color:var(--text);">Session cPanel en cours</div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                    Connecté depuis <strong id="session-timer" style="color:var(--accent);font-family:ui-monospace,monospace;">00:00</strong>
                </div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <form id="end-session-form" action="{{ route('cpanel.end-session') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger" id="end-session-btn">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="10" height="10" rx="1.5"/></svg>
                    J'ai terminé
                </button>
            </form>
        </div>
    </div>
    <p class="text-muted" style="font-size:11px;margin-top:12px;margin-bottom:0;line-height:1.5;">
        Lorsque vous cliquez sur « J'ai terminé », le mot de passe cPanel est immédiatement changé pour sécuriser l'accès.
    </p>
</div>

{{-- ── Sécurité & identifiants ────────────────────────────────────────────── --}}
<div class="card">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border);">
        <div style="width:36px;height:36px;border-radius:8px;background:rgba(220,38,38,0.08);border:1px solid rgba(220,38,38,0.16);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="var(--danger)" stroke-width="1.5"><rect x="3" y="7" width="10" height="7" rx="1.5"/><path d="M5 7V5a3 3 0 116 0v2"/></svg>
        </div>
        <div>
            <div style="font-size:15px;font-weight:700;color:var(--text);">Sécurité & identifiants</div>
            <div style="font-size:12px;color:var(--text-muted);">Mot de passe et rotation automatique</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
        {{-- Mot de passe --}}
        <div>
            <div style="font-size:12px;font-weight:700;color:var(--text);margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="7" width="10" height="7" rx="1.5"/><path d="M5 7V5a3 3 0 116 0v2"/></svg>
                Mot de passe cPanel
            </div>
            @if($password)
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <code id="val-password" style="font-size:13px;padding:7px 12px;background:var(--panel-soft);border:1px solid var(--border);border-radius:6px;letter-spacing:.5px;max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-family:ui-monospace,monospace;">●●●●●●●●●●●●●●●●</code>
                <button type="button" class="btn btn-ghost btn-sm" id="toggle-password" title="Afficher / Masquer" style="padding:4px 6px;">
                    <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="icon-eye"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><circle cx="8" cy="8" r="2.5"/></svg>
                    <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="icon-eye-off" style="display:none;"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><circle cx="8" cy="8" r="2.5"/><line x1="2" y1="2" x2="14" y2="14" stroke-width="1.5"/></svg>
                </button>
                <button type="button" class="btn btn-ghost btn-sm copy-btn" id="copy-password" title="Copier" style="padding:4px 6px;">
                    <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
                </button>
            </div>
            <p class="text-muted" style="font-size:11px;margin-top:8px;">Ne partagez jamais ce mot de passe en dehors de cette interface.</p>
            @else
            <p class="text-muted" style="font-style:italic;font-size:13px;">Non configuré — définir <code class="code">CPANEL_PASSWORD</code> dans <code class="code">.env</code></p>
            @endif
        </div>

        {{-- Rotation --}}
        <div style="border-left:1px solid var(--border);padding-left:24px;">
            <div style="font-size:12px;font-weight:700;color:var(--text);margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 1l6 3v4c0 3.5-2.5 6.5-6 7.5C4.5 14.5 2 11.5 2 8V4l6-3z"/></svg>
                Rotation automatique
            </div>
            <p class="text-muted" style="font-size:12px;margin-bottom:12px;line-height:1.5;">
                Le mot de passe est changé automatiquement <strong style="color:var(--text);">toutes les 4 heures</strong> via une tâche planifiée.
            </p>

            {{-- Dernière rotation --}}
            <div style="background:var(--panel-soft);border:1px solid var(--border);border-radius:8px;padding:10px 14px;margin-bottom:14px;">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);margin-bottom:4px;">Dernière rotation</div>
                @if($lastRotationAt)
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <span style="font-size:13px;font-weight:600;color:var(--text);">{{ $lastRotationAt->format('d/m/Y à H:i:s') }}</span>
                    <span class="badge badge-{{ $lastRotationType === 'Planifiée (cron)' ? 'accent' : ($lastRotationType === 'Manuelle' ? 'warning' : 'success') }}" style="font-size:10px;">{{ $lastRotationType }}</span>
                </div>
                <div class="text-muted" style="font-size:11px;margin-top:2px;">{{ $lastRotationAt->diffForHumans() }}</div>
                @else
                <span class="text-muted" style="font-size:13px;">Aucune rotation enregistrée</span>
                @endif
            </div>

            @if($isSuperAdmin)
            <form id="rotate-form" action="{{ route('cpanel.rotate-password') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm" id="rotate-btn">
                    <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="rotate-icon"><path d="M1.5 8a6.5 6.5 0 0112.48-2.5M14.5 8a6.5 6.5 0 01-12.48 2.5"/><polyline points="14,2 14,5.5 10.5,5.5"/><polyline points="2,14 2,10.5 5.5,10.5"/></svg>
                    Forcer la rotation
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

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
    function hideOverlay() {
        if (overlay) overlay.style.display = 'none';
    }

    // ── Modal d'avertissement ────────────────────────────────────────────────
    var loginModal   = document.getElementById('login-modal');
    var openBtn      = document.getElementById('open-login-modal');
    var cancelBtn    = document.getElementById('modal-cancel');
    var confirmBtn   = document.getElementById('modal-confirm');
    var acceptCb     = document.getElementById('modal-accept');
    var sessionPanel = document.getElementById('session-panel');
    var sessionTimer = document.getElementById('session-timer');
    var timerInterval = null;

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

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
                loginModal.style.display = 'none';
                showOverlay('Connexion en cours…', 'Ouverture de cPanel dans un nouvel onglet.');
                openBtn.disabled = true;

                // Ouvrir l'onglet immédiatement (clic utilisateur = pas bloqué)
                var cpanelTab = window.open('about:blank', '_blank');

                fetch('{{ route("cpanel.manual-login") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
                .then(function (res) {
                    hideOverlay();
                    if (!res.ok || !res.data.url) {
                        if (cpanelTab) cpanelTab.close();
                        openBtn.disabled = false;
                        alert(res.data.error || 'Erreur lors de la connexion.');
                        return;
                    }

                    // Rediriger l'onglet déjà ouvert vers cPanel
                    if (cpanelTab) {
                        cpanelTab.location.href = res.data.url;
                    } else {
                        window.open(res.data.url, '_blank');
                    }

                    // Démarrer le timer de session
                    startSessionTimer();
                })
                .catch(function () {
                    hideOverlay();
                    if (cpanelTab) cpanelTab.close();
                    openBtn.disabled = false;
                    alert('Erreur réseau lors de la connexion.');
                });
            });
        }
    }

    // ── Timer de session ────────────────────────────────────────────────────
    function startSessionTimer() {
        if (sessionPanel) sessionPanel.style.display = '';
        if (openBtn) openBtn.style.display = 'none';

        var seconds = 0;
        function updateDisplay() {
            var h = Math.floor(seconds / 3600);
            var m = Math.floor((seconds % 3600) / 60);
            var s = seconds % 60;
            var parts = [];
            if (h > 0) parts.push(String(h).padStart(2, '0'));
            parts.push(String(m).padStart(2, '0'));
            parts.push(String(s).padStart(2, '0'));
            if (sessionTimer) sessionTimer.textContent = parts.join(':');
        }
        updateDisplay();

        timerInterval = setInterval(function () {
            seconds++;
            updateDisplay();
        }, 1000);
    }

    // ── Fin de session ──────────────────────────────────────────────────────
    var endForm = document.getElementById('end-session-form');
    var endBtn  = document.getElementById('end-session-btn');
    if (endForm) {
        endForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!confirm('Terminer la session ?\n\nLe mot de passe cPanel sera immédiatement changé.')) return;
            if (endBtn) endBtn.disabled = true;
            if (timerInterval) clearInterval(timerInterval);
            showOverlay('Sécurisation en cours…', 'Le mot de passe cPanel est en cours de changement.');
            endForm.submit();
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
        btn.style.color = 'var(--success)';
        setTimeout(function () { btn.innerHTML = original; btn.style.color = ''; }, 1200);
    }
}());
</script>

@endsection
