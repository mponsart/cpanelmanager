@extends('layouts.app')

@section('title', 'Accès cPanel')
@section('page-title', 'Accès cPanel')

@section('content')

@php
    $rows = [
        ['label' => 'Hôte',        'value' => $host,     'sensitive' => false],
        ['label' => 'Port',         'value' => $port,     'sensitive' => false],
        ['label' => 'Utilisateur',  'value' => $username, 'sensitive' => false],
        ['label' => 'Domaine',      'value' => $domain,   'sensitive' => false],
        ['label' => 'Token API',    'value' => $token,    'sensitive' => true],
        ['label' => 'Mot de passe', 'value' => $password, 'sensitive' => true],
    ];

    $secretMap   = [];
    $secretIndex = 0;
    foreach ($rows as $row) {
        if ($row['sensitive'] && !empty($row['value'])) {
            $secretMap[$secretIndex++] = $row['value'];
        }
    }
    $secretIndex = 0;
@endphp

{{-- Page content (blurred until charter accepted) --}}
<div id="cpanel-content" style="filter:blur(8px);pointer-events:none;user-select:none;transition:filter .25s ease;" aria-hidden="true">

<div class="page-header">
    <h1>Accès cPanel</h1>
</div>

<div class="alert alert-warning mb-4" style="display:flex;align-items:center;gap:10px;">
    <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;color:var(--warning);">
        <path d="M8 1.5L1 13.5h14L8 1.5z"/><line x1="8" y1="6" x2="8" y2="9.5"/><circle cx="8" cy="11.5" r=".5" fill="currentColor" stroke="none"/>
    </svg>
    <span><strong>Page réservée aux techniciens autorisés</strong> — Ces informations sont strictement confidentielles. Ne pas partager cette page ni en faire des captures d'écran.</span>
</div>

<div class="card mb-4">
    <div class="card-title">Identifiants cPanel</div>
    <table style="width:100%; border-collapse:collapse;">
        @foreach($rows as $row)
        @php $secretIdx = ($row['sensitive'] && !empty($row['value'])) ? $secretIndex++ : null; @endphp
        <tr style="border-top: 1px solid var(--border);">
            <td class="text-muted" style="width:160px; padding:10px 8px; vertical-align:middle; font-size:.875rem;">
                {{ $row['label'] }}
            </td>
            <td style="padding:10px 8px; vertical-align:middle;">
                @if($row['sensitive'])
                    @if(!empty($row['value']))
                    <div style="display:flex; align-items:center; gap:10px; user-select:none;" oncontextmenu="return false;">
                        <canvas
                            class="secret-canvas"
                            data-idx="{{ $secretIdx }}"
                            height="22"
                            style="display:none; vertical-align:middle; user-select:none;"
                            oncontextmenu="return false;"
                        ></canvas>
                        <button
                            type="button"
                            class="btn btn-ghost btn-sm secret-toggle"
                            style="font-size:12px; flex-shrink:0;"
                            aria-label="Afficher / Masquer"
                        >
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="icon-eye"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><circle cx="8" cy="8" r="2.5"/></svg>
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" class="icon-eye-off" style="display:none;"><path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><circle cx="8" cy="8" r="2.5"/><line x1="2" y1="2" x2="14" y2="14" stroke-width="1.5"/></svg>
                            <span class="toggle-label">Afficher</span>
                        </button>
                    </div>
                    @else
                        <span class="text-muted" style="font-size:.875rem; font-style:italic;">Non configuré</span>
                    @endif
                @else
                    <span style="font-size:.875rem; font-family: 'Courier New', monospace;">
                        {{ $row['value'] ?: '—' }}
                    </span>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>

@if($cpanelUrl)
<div style="margin-top:8px;">
    <a href="{{ $cpanelUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
        <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor"
             stroke-width="1.5" style="vertical-align:middle; margin-right:6px;">
            <path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1v-3"/>
            <polyline points="9,1 15,1 15,7"/>
            <line x1="15" y1="1" x2="7" y2="9"/>
        </svg>
        Ouvrir cPanel
    </a>
</div>
@else
<div class="alert alert-warning" style="margin-top:8px;">
    cPanel n'est pas configuré. Définissez <code>CPANEL_HOST</code> dans votre fichier <code>.env</code>.
</div>
@endif

</div>{{-- /#cpanel-content --}}

{{-- Secrets are injected as a JS variable, never as DOM attributes --}}
<script>
(function () {
    'use strict';

    // ── Secret store ────────────────────────────────────────────────────────
    var _store = {!! json_encode($secretMap, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR) !!};

    function fillRoundRect(ctx, x, y, w, h, r) {
        ctx.beginPath();
        ctx.moveTo(x + r, y);
        ctx.lineTo(x + w - r, y);
        ctx.quadraticCurveTo(x + w, y, x + w, y + r);
        ctx.lineTo(x + w, y + h - r);
        ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
        ctx.lineTo(x + r, y + h);
        ctx.quadraticCurveTo(x, y + h, x, y + h - r);
        ctx.lineTo(x, y + r);
        ctx.quadraticCurveTo(x, y, x + r, y);
        ctx.closePath();
        ctx.fill();
    }

    function drawSecret(canvas, text) {
        var dpr = window.devicePixelRatio || 1;
        var h   = 22;
        var ctx = canvas.getContext('2d');

        ctx.font = '13px "Courier New", monospace';
        var measured = ctx.measureText(text).width;
        var w = Math.ceil(measured) + 20;

        canvas.width  = w * dpr;
        canvas.height = h * dpr;
        canvas.style.width  = w + 'px';
        canvas.style.height = h + 'px';

        ctx.scale(dpr, dpr);
        ctx.clearRect(0, 0, w, h);

        ctx.fillStyle = 'rgba(37,99,235,0.08)';
        if (typeof ctx.roundRect === 'function') {
            ctx.beginPath();
            ctx.roundRect(0, 2, w, h - 4, 6);
            ctx.fill();
        } else {
            fillRoundRect(ctx, 0, 2, w, h - 4, 6);
        }

        ctx.fillStyle = getComputedStyle(document.documentElement).getPropertyValue('--text') || '#0f172a';
        ctx.font = '13px "Courier New", monospace';
        ctx.textBaseline = 'middle';
        ctx.fillText(text, 10, h / 2);
    }

    function clearCanvas(canvas) {
        var ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        canvas.style.width  = '0px';
        canvas.style.height = '0px';
        canvas.width  = 0;
        canvas.height = 0;
    }

    // Map from canvas element → secret string (WeakMap keeps nothing in DOM)
    var secrets = new WeakMap();

    document.querySelectorAll('.secret-canvas').forEach(function (canvas) {
        var idx    = parseInt(canvas.getAttribute('data-idx'), 10);
        var secret = _store[idx] || '';
        canvas.removeAttribute('data-idx');
        secrets.set(canvas, secret);

        canvas.addEventListener('dragstart', function (e) { e.preventDefault(); });

        var wrapper    = canvas.parentElement;
        var btn        = wrapper.querySelector('.secret-toggle');
        var label      = btn.querySelector('.toggle-label');
        var iconEye    = btn.querySelector('.icon-eye');
        var iconEyeOff = btn.querySelector('.icon-eye-off');
        var visible    = false;

        btn.addEventListener('click', function () {
            visible = !visible;
            if (visible) {
                drawSecret(canvas, secrets.get(canvas));
                canvas.style.display = 'inline-block';
                label.textContent    = 'Masquer';
                iconEye.style.display    = 'none';
                iconEyeOff.style.display = '';
            } else {
                clearCanvas(canvas);
                canvas.style.display = 'none';
                label.textContent    = 'Afficher';
                iconEye.style.display    = '';
                iconEyeOff.style.display = 'none';
            }
        });

        btn.addEventListener('contextmenu', function (e) { e.preventDefault(); });
    });

    // Wipe the store from memory — values remain only in the WeakMap above
    _store = null;
}());
</script>

@endsection

{{-- ── Security charter modal (rendered directly in <body> via layout stack) ── --}}
@push('modals')
<style>
@keyframes charter-fadein {
    from { opacity:0; transform:scale(.96); }
    to   { opacity:1; transform:scale(1);   }
}
@keyframes charter-fadeout {
    from { opacity:1; transform:scale(1);   }
    to   { opacity:0; transform:scale(.96); }
}
#security-charter-overlay { animation: charter-fadein .18s cubic-bezier(.4,0,.2,1) both; }
#security-charter-overlay.is-hiding { animation: charter-fadeout .15s cubic-bezier(.4,0,.2,1) both; pointer-events:none; }
</style>

<div id="security-charter-overlay"
     role="dialog"
     aria-modal="true"
     aria-labelledby="charter-title"
     style="position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,0.72);
            display:flex;align-items:center;justify-content:center;
            backdrop-filter:blur(4px);">
    <div style="background:var(--panel);border:1px solid var(--border-strong);
                border-radius:var(--radius);box-shadow:var(--shadow-md);
                max-width:520px;width:calc(100% - 32px);padding:28px 28px 24px;">

        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
            <span style="width:40px;height:40px;flex-shrink:0;border-radius:10px;
                         background:rgba(220,38,38,0.12);
                         display:flex;align-items:center;justify-content:center;">
                <svg width="20" height="20" viewBox="0 0 16 16" fill="none" stroke="#dc2626" stroke-width="1.5" aria-hidden="true">
                    <rect x="3" y="7" width="10" height="8" rx="1.5"/>
                    <path d="M5 7V5a3 3 0 116 0v2"/>
                    <circle cx="8" cy="11" r="1" fill="#dc2626" stroke="none"/>
                </svg>
            </span>
            <h2 id="charter-title" style="font-size:1.05rem;font-weight:700;margin:0;">
                Charte de sécurité — Identifiants cPanel
            </h2>
        </div>

        <p style="font-size:.875rem;color:var(--text-muted);margin-bottom:16px;line-height:1.6;">
            Vous êtes sur le point d'accéder à des
            <strong style="color:var(--text);">informations strictement confidentielles</strong>
            (mots de passe, tokens API). En acceptant cette charte, vous vous engagez à :
        </p>

        <ul style="font-size:.875rem;line-height:1.8;padding-left:18px;margin-bottom:20px;color:var(--text);">
            <li>Ne <strong>pas partager</strong> ces identifiants par e-mail, messagerie ou tout autre canal non sécurisé.</li>
            <li>Ne <strong>pas effectuer de capture d'écran</strong> ou enregistrement de cette page.</li>
            <li>Ne <strong>pas noter</strong> ces informations dans un espace accessible à des tiers.</li>
            <li>Signaler immédiatement toute <strong>compromission suspectée</strong> à l'administrateur principal.</li>
            <li>N'utiliser ces identifiants qu'à des fins <strong>strictement professionnelles et autorisées</strong>.</li>
        </ul>

        <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:20px;">
            Toute violation de cette charte est susceptible d'engager votre responsabilité et de faire l'objet de sanctions.
        </p>

        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <a id="charter-cancel-btn" href="{{ route('dashboard') }}" class="btn btn-ghost btn-sm">
                Annuler
            </a>
            <button id="charter-accept-btn" type="button" class="btn btn-primary btn-sm">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"
                     style="vertical-align:middle;margin-right:5px;" aria-hidden="true">
                    <polyline points="2,8 6,13 14,3"/>
                </svg>
                J'ai lu et j'accepte
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    var CHARTER_KEY = 'cpanel_charter_accepted';
    var overlay   = document.getElementById('security-charter-overlay');
    var content   = document.getElementById('cpanel-content');
    var acceptBtn = document.getElementById('charter-accept-btn');
    var cancelBtn = document.getElementById('charter-cancel-btn');

    // Safety guard — all elements must exist
    if (!overlay || !content || !acceptBtn || !cancelBtn) return;

    var escHandler = null;

    function hideOverlay(instant) {
        // Remove the Escape listener as soon as we start hiding
        if (escHandler) { document.removeEventListener('keydown', escHandler); escHandler = null; }
        if (instant) {
            overlay.style.display = 'none';
            return;
        }
        overlay.classList.add('is-hiding');
        overlay.addEventListener('animationend', function () {
            overlay.style.display = 'none';
        }, { once: true });
    }

    function revealContent() {
        content.style.filter       = '';
        content.style.pointerEvents = '';
        content.style.userSelect    = '';
        content.removeAttribute('aria-hidden');
    }

    // Read sessionStorage safely (may be unavailable in some private-browsing modes)
    var alreadyAccepted = false;
    try { alreadyAccepted = sessionStorage.getItem(CHARTER_KEY) === '1'; } catch (e) {}

    if (alreadyAccepted) {
        hideOverlay(true);
        revealContent();
        return;
    }

    // Auto-focus the accept button so keyboard users can immediately press Enter
    acceptBtn.focus();

    // Accept handler
    acceptBtn.addEventListener('click', function () {
        try { sessionStorage.setItem(CHARTER_KEY, '1'); } catch (e) {}
        hideOverlay(false);
        revealContent();
    });

    // Escape key → cancel (same as clicking "Annuler")
    escHandler = function (e) {
        if (e.key === 'Escape') { window.location.href = cancelBtn.href; }
    };
    document.addEventListener('keydown', escHandler);

    // Focus trap — keep Tab navigation inside the modal.
    // The list is intentionally built once at init; modal content is static.
    var focusable = Array.from(overlay.querySelectorAll('a[href], button:not([disabled])'));
    overlay.addEventListener('keydown', function (e) {
        if (e.key !== 'Tab' || !focusable.length) return;
        var first = focusable[0];
        var last  = focusable[focusable.length - 1];
        if (e.shiftKey) {
            if (document.activeElement === first) { e.preventDefault(); last.focus(); }
        } else {
            if (document.activeElement === last)  { e.preventDefault(); first.focus(); }
        }
    });
}());
</script>
@endpush
