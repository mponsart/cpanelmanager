@extends('layouts.app')

@section('title', 'Accès cPanel')
@section('page-title', 'Accès cPanel')

@section('content')

{{-- ── Bannière d'avertissement ───────────────────────────────────────────── --}}
<div class="alert alert-warning" style="margin-bottom:20px;">
    <strong>Rappel :</strong> l'accès direct à cPanel doit rester exceptionnel. Utilisez-le uniquement pour les fonctionnalités non disponibles dans ce panneau.
</div>

{{-- ── Alerte session active (autre utilisateur) ─────────────────────────── --}}
@if($activeSessionUser && $activeSessionUser->id !== auth()->id())
<div class="alert alert-error" style="margin-bottom:20px;display:flex;align-items:center;gap:12px;">
    <svg width="20" height="20" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;"><path d="M8 1l7 13H1L8 1z"/><line x1="8" y1="6" x2="8" y2="9"/><circle cx="8" cy="11" r="0.5" fill="currentColor" stroke="none"/></svg>
    <div>
        <strong>Session active !</strong>
        <strong style="color:var(--text);">{{ $activeSessionUser->name }}</strong> est connecté(e) à cPanel depuis {{ $activeSessionSince->diffForHumans(null, false, false, 2) }}.
        <span style="display:block;font-size:12px;margin-top:4px;opacity:.85;">Se connecter en même temps peut provoquer des conflits. Coordonnez-vous avant de continuer.</span>
    </div>
</div>
@endif

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
            @if($activeSessionUser && $activeSessionUser->id === auth()->id())
            {{-- Session en cours pour l'utilisateur actuel: pas de bouton --}}
            @elseif($activeSessionUser && $activeSessionUser->id !== auth()->id())
            <span class="btn btn-ghost" style="opacity:0.5;cursor:not-allowed;pointer-events:none;">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="7" width="10" height="7" rx="1.5"/><path d="M5 7V5a3 3 0 116 0v2"/></svg>
                Accès verrouillé
            </span>
            @else
            <button type="button" class="btn btn-primary" id="open-login-modal">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1v-3"/><polyline points="9,1 15,1 15,7"/><line x1="15" y1="1" x2="7" y2="9"/></svg>
                Se connecter
            </button>
            @endif
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

{{-- ── Bandeau session active ────────────────────────────────────────────── --}}
@php
    $myActiveSession = $activeSessionUser && $activeSessionUser->id === auth()->id();
@endphp
<div id="session-panel" class="card" style="{{ $myActiveSession ? '' : 'display:none;' }}margin-bottom:20px;border:1px solid rgba(124,58,237,0.25);background:linear-gradient(135deg,rgba(124,58,237,0.04),rgba(124,58,237,0.08));">
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
            <button type="button" class="btn btn-danger" id="open-end-modal">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="10" height="10" rx="1.5"/></svg>
                J'ai terminé
            </button>
        </div>
    </div>
    <p class="text-muted" style="font-size:11px;margin-top:12px;margin-bottom:0;line-height:1.5;">
        Lorsque vous cliquez sur « J'ai terminé », vous devrez décrire les actions effectuées. Le mot de passe sera ensuite changé.
    </p>
</div>

{{-- ── Modal rapport de session ──────────────────────────────────────────── --}}
<div id="end-session-modal" class="cp-modal" aria-hidden="true">
    <div class="cp-modal-dialog cp-modal-dialog-lg" role="dialog" aria-modal="true" aria-labelledby="end-modal-title">
        <div class="cp-modal-head cp-modal-head-danger">
            <div class="cp-modal-head-row">
                <div class="cp-modal-icon cp-modal-icon-danger">
                    <svg width="22" height="22" viewBox="0 0 16 16" fill="none" stroke="#dc2626" stroke-width="1.5"><path d="M14 2H2v12h12V2z"/><path d="M5 5h6M5 8h4"/></svg>
                </div>
                <div>
                    <div id="end-modal-title" class="cp-modal-title">Rapport de session</div>
                    <div class="cp-modal-subtitle">Décrivez les actions effectuées sur cPanel</div>
                </div>
            </div>
        </div>

        <form id="end-session-form" action="{{ route('cpanel.end-session') }}" method="POST">
            @csrf
            <div class="cp-modal-body">
                <label for="session-description" class="cp-field-label">Qu'avez-vous fait sur cPanel ?</label>
                <textarea id="session-description" name="description" rows="4" required minlength="10" maxlength="2000" placeholder="Ex : Modification des enregistrements DNS du domaine example.com, ajout d'un sous-domaine api.example.com…" class="cp-textarea"></textarea>
                <p class="cp-help">Minimum 10 caractères. Soyez précis : domaines, comptes, fichiers modifiés, etc.</p>

                <label class="cp-attest cp-attest-danger">
                    <input type="checkbox" id="end-session-attest">
                    <span>J'atteste sur l'honneur que la description ci-dessus est exacte et complète. Je suis conscient(e) que toute fausse déclaration pourra entraîner des sanctions.</span>
                </label>
            </div>

            <div class="cp-modal-foot">
                <button type="button" class="btn btn-ghost" id="end-modal-cancel">Annuler</button>
                <button type="submit" class="btn btn-danger" id="end-modal-confirm" disabled>
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="10" height="10" rx="1.5"/></svg>
                    Terminer et sécuriser
                </button>
            </div>
        </form>
    </div>
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
                <code id="val-password" style="font-size:13px;padding:7px 12px;background:var(--panel-soft);border:1px solid var(--border);border-radius:6px;letter-spacing:.5px;max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-family:ui-monospace,monospace;user-select:none;-webkit-user-select:none;">●●●●●●●●●●●●●●●●</code>
                @if($activeSessionUser && $activeSessionUser->id === auth()->id())
                <button type="button" class="btn btn-ghost btn-sm copy-btn" id="copy-password" title="Copier" style="padding:4px 6px;">
                    <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="5" width="9" height="9" rx="1"/><path d="M5 11H3.5A1.5 1.5 0 012 9.5v-7A1.5 1.5 0 013.5 1h7A1.5 1.5 0 0112 2.5V5"/></svg>
                </button>
                @endif
            </div>
            <p class="text-muted" style="font-size:11px;margin-top:8px;">
                Le mot de passe ne peut pas être affiché.@if($activeSessionUser && $activeSessionUser->id === auth()->id()) Vous pouvez le copier si besoin.@endif
            </p>
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
                Le mot de passe est changé automatiquement <strong style="color:var(--text);">avant connexion cPanel</strong> si la dernière rotation a plus de {{ (int) config('cpanel.rotation_hours', 4) }} heure(s).
            </p>

            {{-- Dernière rotation --}}
            <div style="background:var(--panel-soft);border:1px solid var(--border);border-radius:8px;padding:10px 14px;margin-bottom:14px;">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);margin-bottom:4px;">Dernière rotation</div>
                @if($lastRotationAt)
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <span style="font-size:13px;font-weight:600;color:var(--text);">{{ $lastRotationAt->format('d/m/Y à H:i:s') }}</span>
                    <span class="badge badge-{{ $lastRotationType === 'Manuelle' ? 'warning' : 'success' }}" style="font-size:10px;">{{ $lastRotationType }}</span>
                </div>
                <div class="text-muted" style="font-size:11px;margin-top:2px;">{{ $lastRotationAt->diffForHumans() }}</div>
                @else
                <span class="text-muted" style="font-size:13px;">Aucune rotation enregistrée</span>
                @endif
            </div>

            {{-- Rotation manuelle supprimée - rotation uniquement en début et fin de session --}}
        </div>
    </div>
</div>

{{-- ── Modal d'avertissement avant connexion ─────────────────────────────── --}}
<div id="login-modal" class="cp-modal" aria-hidden="true">
    <div class="cp-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="login-modal-title">
        <div class="cp-modal-head cp-modal-head-warning">
            <div class="cp-modal-head-row">
                <div class="cp-modal-icon cp-modal-icon-warning">
                    <svg width="22" height="22" viewBox="0 0 16 16" fill="none" stroke="#d97706" stroke-width="1.5"><path d="M8 1l6 3v4c0 3.5-2.5 6.5-6 7.5C4.5 14.5 2 11.5 2 8V4l6-3z"/><line x1="8" y1="5.5" x2="8" y2="8.5"/><circle cx="8" cy="10.5" r="0.5" fill="#d97706" stroke="none"/></svg>
                </div>
                <div>
                    <div id="login-modal-title" class="cp-modal-title">Accès limité</div>
                    <div class="cp-modal-subtitle">Connexion directe à cPanel</div>
                </div>
            </div>
        </div>

        <div class="cp-modal-body">
            <p class="cp-modal-text">Ces identifiants donnent accès à l'ensemble de cPanel. Merci de respecter ces règles :</p>
            <ul class="cp-rules">
                <li>Utiliser <strong>uniquement</strong> pour les fonctionnalités non disponibles dans ce panneau</li>
                <li>Limiter la durée de la session au strict nécessaire</li>
                <li>Ne jamais partager ou enregistrer les identifiants</li>
                <li>Toute action est journalisée et auditée</li>
            </ul>
            <label class="cp-check">
                <input type="checkbox" id="modal-accept">
                <span>J'ai lu et j'accepte ces conditions d'utilisation</span>
            </label>
            <div id="login-modal-error" class="alert alert-error" style="display:none;margin-top:14px;"></div>
        </div>

        <div class="cp-modal-foot">
            <button type="button" class="btn btn-ghost" id="modal-cancel">Annuler</button>
            <button type="button" class="btn btn-primary" id="modal-confirm" disabled>
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1v-3"/><polyline points="9,1 15,1 15,7"/><line x1="15" y1="1" x2="7" y2="9"/></svg>
                Continuer vers cPanel
            </button>
        </div>
    </div>
</div>

{{-- Modal de rotation manuelle supprimée --}}
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
<style>
@keyframes spin{to{transform:rotate(360deg)}}

.cp-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(15,23,42,0.50);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    justify-content: center;
    align-items: center;
    padding: 16px;
}

.cp-modal-dialog {
    background: var(--panel);
    border: 1px solid var(--border);
    border-radius: 16px;
    box-shadow: 0 12px 40px rgba(15,23,42,0.20);
    width: 100%;
    max-width: 500px;
    overflow: hidden;
}

.cp-modal-dialog-lg {
    max-width: 560px;
}

.cp-modal-head {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
}

.cp-modal-head-warning {
    background: linear-gradient(135deg,#fef3c7,#fde68a);
    border-bottom-color: #fde68a;
}

.cp-modal-head-warning-soft {
    background: linear-gradient(135deg,#fff8e1,#ffefbf);
    border-bottom-color: #f8e5ac;
}

.cp-modal-head-danger {
    background: linear-gradient(135deg,#fef2f2,#fecaca);
    border-bottom-color: #fecaca;
}

.cp-modal-head-row {
    display: flex;
    align-items: center;
    gap: 12px;
}

.cp-modal-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.cp-modal-icon-warning {
    background: #fffbeb;
    border: 1px solid #fcd34d;
    color: #d97706;
}

.cp-modal-icon-danger {
    background: #fff5f5;
    border: 1px solid #fca5a5;
}

.cp-modal-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
}

.cp-modal-subtitle {
    font-size: 12px;
    margin-top: 2px;
    color: var(--text-muted);
}

.cp-modal-body {
    padding: 22px 24px;
}

.cp-modal-text {
    font-size: 14px;
    color: var(--text);
    line-height: 1.65;
    margin: 0;
}

.cp-rules {
    font-size: 13px;
    color: var(--text-muted);
    line-height: 1.7;
    margin: 14px 0 18px;
    padding-left: 18px;
}

.cp-rules li { margin-bottom: 6px; }
.cp-rules li:last-child { margin-bottom: 0; }
.cp-rules strong { color: var(--text); }

.cp-check {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    cursor: pointer;
    font-size: 13px;
    color: var(--text);
    font-weight: 500;
}

.cp-check input,
.cp-attest input {
    margin-top: 3px;
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    accent-color: var(--accent);
}

.cp-field-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 8px;
}

.cp-textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--panel-soft);
    color: var(--text);
    font-size: 13px;
    line-height: 1.6;
    resize: vertical;
    font-family: inherit;
    box-sizing: border-box;
    min-height: 100px;
}

.cp-help {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 8px;
    line-height: 1.55;
}

.cp-attest {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    cursor: pointer;
    font-size: 13px;
    color: var(--text);
    font-weight: 500;
    margin-top: 16px;
    padding: 12px 14px;
    border-radius: 8px;
}

.cp-attest-danger {
    background: rgba(220,38,38,0.04);
    border: 1px solid rgba(220,38,38,0.12);
}

.cp-attest-danger input {
    accent-color: #dc2626;
}

.cp-modal-foot {
    padding: 16px 24px;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    background: var(--panel-soft);
}

.cp-modal-foot-plain {
    background: var(--panel);
}

@media (max-width: 680px) {
    .cp-modal-head,
    .cp-modal-body,
    .cp-modal-foot {
        padding-left: 16px;
        padding-right: 16px;
    }
}
</style>

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

    function setActionEnabled(button, enabled) {
        if (!button) return;
        button.disabled = !enabled;
        button.style.opacity = enabled ? '1' : '0.5';
    }

    function openModal(modal) {
        if (!modal) return;
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeModal(modal) {
        if (!modal) return;
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
    }

    function bindBackdropClose(modal, closeButton) {
        if (closeButton) {
            closeButton.addEventListener('click', function () {
                closeModal(modal);
            });
        }

        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal(modal);
            });
        }
    }

    // ── Modal d'avertissement ────────────────────────────────────────────────
    var loginModal   = document.getElementById('login-modal');
    var openBtn      = document.getElementById('open-login-modal');
    var cancelBtn    = document.getElementById('modal-cancel');
    var confirmBtn   = document.getElementById('modal-confirm');
    var acceptCb     = document.getElementById('modal-accept');
    var loginErrorEl = document.getElementById('login-modal-error');
    var sessionPanel = document.getElementById('session-panel');
    var sessionTimer = document.getElementById('session-timer');
    var timerInterval = null;

    if (openBtn && loginModal) {
        openBtn.addEventListener('click', function () {
            openModal(loginModal);
            if (loginErrorEl) {
                loginErrorEl.style.display = 'none';
                loginErrorEl.textContent = '';
            }
            if (acceptCb) { acceptCb.checked = false; }
            setActionEnabled(confirmBtn, false);
        });

        bindBackdropClose(loginModal, cancelBtn);

        if (acceptCb && confirmBtn) {
            acceptCb.addEventListener('change', function () {
                setActionEnabled(confirmBtn, acceptCb.checked);
            });
        }

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
                closeModal(loginModal);
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
                        if (loginErrorEl) {
                            loginErrorEl.textContent = res.data.error || 'Erreur lors de la connexion.';
                            loginErrorEl.style.display = 'flex';
                        }
                        return;
                    }

                    // Rediriger l'onglet déjà ouvert vers cPanel
                    if (cpanelTab) {
                        cpanelTab.location.href = res.data.url;
                    } else {
                        window.open(res.data.url, '_blank');
                    }

                    // Démarrer le timer de session
                    startSessionTimer(0);
                })
                .catch(function () {
                    hideOverlay();
                    if (cpanelTab) cpanelTab.close();
                    openBtn.disabled = false;
                    if (loginErrorEl) {
                        loginErrorEl.textContent = 'Erreur réseau lors de la connexion.';
                        loginErrorEl.style.display = 'flex';
                    }
                });
            });
        }
    }

    // ── Timer de session ────────────────────────────────────────────────────
    function startSessionTimer(initialSeconds) {
        if (sessionPanel) sessionPanel.style.display = '';
        if (openBtn) openBtn.style.display = 'none';

        var seconds = initialSeconds || 0;
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

    // ── Reprise du timer si session active au chargement ────────────────────
    @if($myActiveSession && $activeSessionSince)
    (function () {
        var startedAt = new Date(@json($activeSessionSince->toIso8601String()));
        var elapsed = Math.floor((Date.now() - startedAt.getTime()) / 1000);
        startSessionTimer(Math.max(0, elapsed));
    })();
    @endif

    // ── Fin de session (modale rapport) ────────────────────────────────────
    var endModal      = document.getElementById('end-session-modal');
    var openEndBtn    = document.getElementById('open-end-modal');
    var endCancelBtn  = document.getElementById('end-modal-cancel');
    var endConfirmBtn = document.getElementById('end-modal-confirm');
    var endAttest     = document.getElementById('end-session-attest');
    var endDesc       = document.getElementById('session-description');
    var endForm       = document.getElementById('end-session-form');

    if (openEndBtn && endModal) {
        openEndBtn.addEventListener('click', function () {
            openModal(endModal);
        });

        bindBackdropClose(endModal, endCancelBtn);

        function updateEndConfirm() {
            var valid = endAttest && endAttest.checked && endDesc && endDesc.value.trim().length >= 10;
            setActionEnabled(endConfirmBtn, !!valid);
        }

        if (endAttest) endAttest.addEventListener('change', updateEndConfirm);
        if (endDesc) endDesc.addEventListener('input', updateEndConfirm);

        if (endForm) {
            endForm.addEventListener('submit', function (e) {
                e.preventDefault();
                if (endConfirmBtn.disabled) return;
                endConfirmBtn.disabled = true;
                if (timerInterval) clearInterval(timerInterval);
                closeModal(endModal);
                showOverlay('Sécurisation en cours…', 'Enregistrement du rapport et changement du mot de passe.');
                endForm.submit();
            });
        }
    }

    // Rotation manuelle supprimée - rotation uniquement en début et fin de session
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        [loginModal, endModal].forEach(function (modal) {
            if (modal && modal.style.display === 'flex') closeModal(modal);
        });
    });

    // Affichage désactivé, copie autorisée uniquement pour l'utilisateur connecté
    var _pw       = @json($password);
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
