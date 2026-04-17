@extends('layouts.app')

@section('title', 'Identifiants confidentiels')
@section('page-title', 'Identifiants confidentiels')

@section('content')

@php
    $secretIndex = 0;
    $secretMap   = [];
    foreach ($credentials as $section) {
        foreach ($section['rows'] as $row) {
            if ($row['sensitive'] && !empty($row['value'])) {
                $secretMap[$secretIndex++] = $row['value'];
            }
        }
    }
    // Reset counter for the template loop below
    $secretIndex = 0;
@endphp

<div class="page-header">
    <h1>Identifiants confidentiels</h1>
</div>

<div class="alert alert-warning mb-4" style="display:flex;align-items:center;gap:10px;">
    <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0;color:var(--warning);">
        <path d="M8 1.5L1 13.5h14L8 1.5z"/><line x1="8" y1="6" x2="8" y2="9.5"/><circle cx="8" cy="11.5" r=".5" fill="currentColor" stroke="none"/>
    </svg>
    <span><strong>Page réservée aux administrateurs</strong> — Ces informations sont strictement confidentielles. Ne pas partager cette page ni en faire des captures d'écran.</span>
</div>

@foreach($credentials as $section)
<div class="card mb-4">
    <div class="card-title">{{ $section['section'] }}</div>
    <table style="width:100%; border-collapse:collapse;">
        @foreach($section['rows'] as $row)
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
@endforeach

{{-- Secrets are injected as a JS variable, never as DOM attributes --}}
<script>
(function () {
    'use strict';

    // Secret store: index → value. Cleared immediately after canvas init.
    var _store = {!! json_encode($secretMap) !!};

    // Rounded-rect fallback for browsers without CanvasRenderingContext2D.roundRect
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

        // Pill background
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

