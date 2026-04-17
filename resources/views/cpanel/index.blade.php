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

<div class="page-header">
    <h1>Accès cPanel</h1>
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

{{-- Secrets are injected as a JS variable, never as DOM attributes --}}
<script>
(function () {
    'use strict';

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

    _store = null;
}());
</script>

@endsection
