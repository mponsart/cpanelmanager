@extends('layouts.app')

@section('title', 'Journaux cPanel')
@section('page-title', 'Journaux cPanel')

@section('content')

{{-- ── Filtres ────────────────────────────────────────────────────────────── --}}
<div class="card" style="margin-bottom:20px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
        <div style="width:32px;height:32px;border-radius:8px;background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.16);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="var(--accent)" stroke-width="1.5"><path d="M1 2h14l-5.5 6v5l-3 1.5V8L1 2z"/></svg>
        </div>
        <div style="font-size:14px;font-weight:700;color:var(--text);">Filtres</div>
    </div>
    <form method="GET" action="{{ route('cpanel.logs') }}">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
            <div class="form-group">
                <label style="font-size:12px;font-weight:600;">Action</label>
                <select name="action" style="width:100%;">
                    <option value="">Toutes</option>
                    @foreach($actions as $a)
                        <option value="{{ $a }}" {{ request('action') === $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label style="font-size:12px;font-weight:600;">Utilisateur</label>
                <select name="user_id" style="width:100%;">
                    <option value="">Tous</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label style="font-size:12px;font-weight:600;">Du</label>
                <input type="date" name="from" value="{{ request('from') }}" style="width:100%;">
            </div>
            <div class="form-group">
                <label style="font-size:12px;font-weight:600;">Au</label>
                <input type="date" name="to" value="{{ request('to') }}" style="width:100%;">
            </div>
        </div>
        <div style="display:flex;gap:8px;margin-top:12px;">
            <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
            <a href="{{ route('cpanel.logs') }}" class="btn btn-ghost btn-sm">Réinitialiser</a>
        </div>
    </form>
</div>

{{-- ── Timeline des logs ─────────────────────────────────────────────────── --}}
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:32px;height:32px;border-radius:8px;background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.16);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="var(--accent)" stroke-width="1.5"><path d="M2 2v12h12"/><path d="M5 10l3-4 3 2 3-5"/></svg>
            </div>
            <div>
                <div style="font-size:14px;font-weight:700;color:var(--text);">Historique des actions</div>
                <div style="font-size:12px;color:var(--text-muted);">{{ $logs->total() }} entrée{{ $logs->total() > 1 ? 's' : '' }}</div>
            </div>
        </div>
        @if(auth()->user()?->isSuperAdmin() && $logs->total() > 0)
        <form id="clear-logs-form" action="{{ route('cpanel.logs.clear') }}" method="POST" style="margin:0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" id="clear-logs-btn">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="3,6 3,14 13,14 13,6"/><line x1="1" y1="3" x2="15" y2="3"/><path d="M6 3V1.5h4V3"/><line x1="6.5" y1="8.5" x2="6.5" y2="11.5"/><line x1="9.5" y1="8.5" x2="9.5" y2="11.5"/></svg>
                Purger les journaux
            </button>
        </form>
        @endif
    </div>

    @forelse($logs as $log)
    @php
        $actionLabels = [
            'cpanel_manual_login'              => ['Connexion cPanel', 'accent', 'M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1v-3 M9,1 15,1 15,7 M15 1L7 9'],
            'cpanel_session_report'            => ['Rapport de session', 'info', 'M14 2H2v12h12V2z M5 5h6 M5 8h4'],
            'cpanel_end_session_rotate'        => ['Fin de session (rotation)', 'success', 'M1.5 8a6.5 6.5 0 0112.48-2.5M14.5 8a6.5 6.5 0 01-12.48 2.5'],
            'cpanel_force_rotate_password'     => ['Rotation manuelle', 'warning', 'M1.5 8a6.5 6.5 0 0112.48-2.5M14.5 8a6.5 6.5 0 01-12.48 2.5'],
            'cpanel_scheduled_rotate_password' => ['Rotation planifiée', 'accent', 'M8 1.5V8l3 3 M8 14.5a6.5 6.5 0 100-13 6.5 6.5 0 000 13z'],
        ];
        $info = $actionLabels[$log->action] ?? [$log->action, 'ghost', 'M8 1v14 M1 8h14'];
        $badgeColors = [
            'accent'  => 'background:rgba(124,58,237,0.08);color:var(--accent);border-color:rgba(124,58,237,0.20)',
            'success' => 'background:#f0fdf4;color:#166534;border-color:#bbf7d0',
            'warning' => 'background:#fffbeb;color:#92400e;border-color:#fde68a',
            'info'    => 'background:#eff6ff;color:#1e40af;border-color:#bfdbfe',
            'ghost'   => 'background:var(--panel-soft);color:var(--text-muted);border-color:var(--border)',
        ];
        $statusColors = [
            'success' => '#16a34a',
            'error'   => '#dc2626',
            'denied'  => '#d97706',
        ];
    @endphp
    <div style="display:flex;gap:14px;padding:16px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--border);' : '' }}">
        {{-- Indicateur de statut --}}
        <div style="flex-shrink:0;margin-top:2px;">
            <div style="width:10px;height:10px;border-radius:50%;background:{{ $statusColors[$log->status] ?? '#94a3b8' }};box-shadow:0 0 0 3px {{ ($statusColors[$log->status] ?? '#94a3b8') }}20;"></div>
        </div>

        {{-- Contenu --}}
        <div style="flex:1;min-width:0;">
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:4px;">
                <span style="font-size:11px;padding:2px 8px;border-radius:4px;border:1px solid;font-weight:600;{{ $badgeColors[$info[1]] }}">{{ $info[0] }}</span>
                @if($log->status === 'error')
                <span style="font-size:10px;padding:1px 6px;border-radius:4px;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;font-weight:600;">Erreur</span>
                @endif
                <span style="font-size:12px;color:var(--text-muted);">{{ $log->created_at->format('d/m/Y à H:i:s') }}</span>
            </div>

            <div style="display:flex;align-items:center;gap:6px;margin-bottom:2px;">
                @if($log->user)
                <span style="font-size:13px;font-weight:600;color:var(--text);">{{ $log->user->name }}</span>
                @else
                <span style="font-size:13px;font-weight:600;color:var(--text-muted);font-style:italic;">Système (cron)</span>
                @endif
                <span style="font-size:12px;color:var(--text-muted);">·</span>
                <span style="font-size:12px;color:var(--text-muted);font-family:ui-monospace,monospace;">{{ $log->ip ?? '—' }}</span>
            </div>

            {{-- Rapport de session --}}
            @if($log->action === 'cpanel_session_report' && !empty($log->payload['description']))
            <div style="margin-top:8px;background:{{ !empty($log->payload['flagged_intrusion']) ? '#fef2f2' : 'var(--panel-soft)' }};border:1px solid {{ !empty($log->payload['flagged_intrusion']) ? '#fecaca' : 'var(--border)' }};border-radius:8px;padding:12px 14px;">
                @if(!empty($log->payload['flagged_intrusion']))
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;padding:6px 10px;background:#dc2626;border-radius:6px;color:#fff;font-size:11px;font-weight:700;">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 1l7 13H1L8 1z"/><line x1="8" y1="6" x2="8" y2="9"/><circle cx="8" cy="11" r="0.5" fill="currentColor" stroke="none"/></svg>
                    SIGNALÉ COMME INTRUSION
                    <span style="font-weight:400;margin-left:4px;">— par {{ $log->payload['flagged_by'] ?? '?' }} le {{ isset($log->payload['flagged_at']) ? \Carbon\Carbon::parse($log->payload['flagged_at'])->format('d/m/Y à H:i') : '?' }}</span>
                </div>
                @if(!empty($log->payload['flagged_reason']))
                <div style="font-size:12px;color:#991b1b;margin-bottom:8px;padding:8px 10px;background:#fff5f5;border:1px solid #fecaca;border-radius:6px;line-height:1.5;">
                    <strong>Motif :</strong> {{ $log->payload['flagged_reason'] }}
                </div>
                @endif
                @endif
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);margin-bottom:6px;">Rapport</div>
                <div style="font-size:13px;color:var(--text);line-height:1.65;white-space:pre-wrap;">{{ $log->payload['description'] }}</div>
                <div style="display:flex;align-items:center;gap:14px;margin-top:10px;padding-top:8px;border-top:1px solid var(--border);font-size:11px;color:var(--text-muted);flex-wrap:wrap;">
                    @if(!empty($log->payload['session_duration']))
                    <span>⏱ Durée : <strong style="color:var(--text);">{{ $log->payload['session_duration'] }}</strong></span>
                    @endif
                    @if(!empty($log->payload['attested']))
                    <span style="color:#166534;">✓ Attesté sur l'honneur</span>
                    @endif
                    @if(auth()->user()?->isSuperAdmin() && empty($log->payload['flagged_intrusion']))
                    <button type="button" class="btn btn-ghost btn-sm flag-intrusion-btn" data-log-id="{{ $log->id }}" style="margin-left:auto;font-size:11px;color:#dc2626;padding:2px 8px;border:1px solid #fecaca;">
                        <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 1l7 13H1L8 1z"/><line x1="8" y1="6" x2="8" y2="9"/><circle cx="8" cy="11" r="0.5" fill="currentColor" stroke="none"/></svg>
                        Signaler comme intrusion
                    </button>
                    @endif
                </div>
            </div>
            @endif

            {{-- Erreur --}}
            @if($log->error_message)
            <div style="margin-top:6px;font-size:12px;color:#dc2626;line-height:1.5;">
                {{ $log->error_message }}
            </div>
            @endif

            {{-- Cible --}}
            @if($log->target && $log->action !== 'cpanel_session_report')
            <div style="margin-top:4px;font-size:12px;color:var(--text-muted);">
                Cible : <span style="font-family:ui-monospace,monospace;">{{ $log->target }}</span>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:40px 20px;">
        <svg width="40" height="40" viewBox="0 0 16 16" fill="none" stroke="var(--text-muted)" stroke-width="1" style="opacity:0.4;margin-bottom:12px;"><path d="M2 2v12h12"/><path d="M5 10l3-4 3 2 3-5"/></svg>
        <p style="font-size:14px;color:var(--text-muted);font-weight:500;">Aucun journal trouvé</p>
        <p style="font-size:12px;color:var(--text-muted);margin-top:4px;">Modifiez vos filtres ou revenez plus tard.</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($logs->hasPages())
    <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);display:flex;justify-content:center;">
        {{ $logs->links() }}
    </div>
    @endif
</div>

{{-- ── Modal signalement intrusion ───────────────────────────────────────── --}}
@if(auth()->user()?->isSuperAdmin())
<div id="flag-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,0.50);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);justify-content:center;align-items:center;">
    <div style="background:var(--panel);border:1px solid var(--border);border-radius:14px;padding:0;box-shadow:0 12px 40px rgba(15,23,42,0.20);max-width:480px;width:92%;overflow:hidden;">
        <div style="background:linear-gradient(135deg,#fef2f2,#fecaca);padding:20px 24px;border-bottom:1px solid #fecaca;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:42px;height:42px;border-radius:11px;background:#fff5f5;border:1px solid #fca5a5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 16 16" fill="none" stroke="#dc2626" stroke-width="1.5"><path d="M8 1l7 13H1L8 1z"/><line x1="8" y1="6" x2="8" y2="9"/><circle cx="8" cy="11" r="0.5" fill="#dc2626" stroke="none"/></svg>
                </div>
                <div>
                    <div style="font-size:16px;font-weight:700;color:#991b1b;">Signaler une intrusion</div>
                    <div style="font-size:12px;color:#b91c1c;margin-top:2px;">Cette action est irréversible et sera journalisée</div>
                </div>
            </div>
        </div>
        <form id="flag-form" method="POST" action="">
            @csrf
            <div style="padding:24px;">
                <p style="font-size:13px;color:var(--text);line-height:1.5;margin-bottom:16px;">
                    En signalant ce rapport comme intrusion, vous indiquez que la justification fournie par l'utilisateur est insuffisante ou mensongère, et que l'accès est considéré comme non autorisé.
                </p>
                <label for="flag-reason" style="display:block;font-size:13px;font-weight:600;color:var(--text);margin-bottom:8px;">Motif du signalement</label>
                <textarea id="flag-reason" name="reason" rows="3" required minlength="5" maxlength="1000"
                    placeholder="Ex : L'utilisateur déclare avoir modifié des DNS mais aucune modification n'a été constatée…"
                    style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:8px;background:var(--panel-soft);color:var(--text);font-size:13px;line-height:1.5;resize:vertical;font-family:inherit;box-sizing:border-box;"></textarea>
            </div>
            <div style="padding:16px 24px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:flex-end;gap:10px;background:var(--panel-soft);">
                <button type="button" class="btn btn-ghost" id="flag-cancel">Annuler</button>
                <button type="submit" class="btn btn-danger" id="flag-confirm">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 1l7 13H1L8 1z"/><line x1="8" y1="6" x2="8" y2="9"/><circle cx="8" cy="11" r="0.5" fill="currentColor" stroke="none"/></svg>
                    Confirmer le signalement
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('clear-logs-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!confirm('Êtes-vous sûr de vouloir purger tous les journaux cPanel ?\n\nCette action est irréversible.')) return;
            document.getElementById('clear-logs-btn').disabled = true;
            form.submit();
        });
    }

    // ── Signalement intrusion ───────────────────────────────────────────────
    var flagModal  = document.getElementById('flag-modal');
    var flagForm   = document.getElementById('flag-form');
    var flagCancel = document.getElementById('flag-cancel');

    if (flagModal) {
        document.querySelectorAll('.flag-intrusion-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var logId = btn.getAttribute('data-log-id');
                flagForm.action = '{{ url("/acces-cpanel/logs") }}/' + logId + '/flag-intrusion';
                document.getElementById('flag-reason').value = '';
                flagModal.style.display = 'flex';
            });
        });

        if (flagCancel) {
            flagCancel.addEventListener('click', function () {
                flagModal.style.display = 'none';
            });
        }

        flagModal.addEventListener('click', function (e) {
            if (e.target === flagModal) flagModal.style.display = 'none';
        });
    }
}());
</script>
@endpush
