@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@php
    $incidents = $recentLogs->whereIn('status', ['error', 'denied'])->take(8);
    $recentActivity = $recentLogs->reject(fn ($log) => in_array($log->status, ['error', 'denied'], true))->take(10);
@endphp

<style>
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .kpi-card {
        background: var(--panel);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px 20px;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .kpi-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .kpi-icon-blue   { background: #eff6ff; color: #2563eb; }
    .kpi-icon-green  { background: #f0fdf4; color: #16a34a; }
    .kpi-icon-red    { background: #fef2f2; color: #dc2626; }
    .kpi-icon-amber  { background: #fffbeb; color: #d97706; }

    .kpi-body { flex: 1; min-width: 0; }
    .kpi-label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
    .kpi-value { font-size: 26px; font-weight: 700; color: var(--text); line-height: 1.2; margin-top: 3px; }

    .shortcuts-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .shortcut-card {
        background: var(--panel);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px;
        box-shadow: var(--shadow-sm);
        transition: border-color 0.12s, box-shadow 0.12s;
    }

    .shortcut-card:hover {
        border-color: #bfdbfe;
        box-shadow: 0 4px 16px rgba(37,99,235,0.08);
    }

    .shortcut-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .shortcut-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .shortcut-title { font-size: 13px; font-weight: 600; color: var(--text); }
    .shortcut-desc  { font-size: 11.5px; color: var(--text-muted); margin-bottom: 10px; }

    .shortcut-links { display: flex; flex-wrap: wrap; gap: 6px; }

    .shortcut-links .btn-ghost {
        font-size: 11.5px;
        padding: 4px 10px;
        background: var(--panel-soft);
    }

    .activity-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .activity-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }

    .activity-header .title { font-size: 14px; font-weight: 600; }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .status-dot-error   { background: var(--danger); }
    .status-dot-warning { background: var(--warning); }
    .status-dot-success { background: var(--success); }

    .dash-search-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .dash-search {
        position: relative;
        min-width: 240px;
    }

    .dash-search svg {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }

    .dash-search input {
        width: 100%;
        padding: 8px 12px 8px 34px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--panel);
        font-size: 13px;
        color: var(--text);
        outline: none;
        transition: all 0.12s;
    }

    .dash-search input:focus {
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.10);
    }

    @media (max-width: 1100px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .shortcuts-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 860px) {
        .activity-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
        .kpi-grid { grid-template-columns: 1fr 1fr; }
        .shortcuts-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="dash-search-row">
    <div class="dash-search">
        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="7" cy="7" r="4.5"/><line x1="10.5" y1="10.5" x2="14" y2="14"/>
        </svg>
        <input type="search" placeholder="Filtrer les incidents et logs…" data-dashboard-search aria-label="Filtrer les incidents et logs">
    </div>
    <a href="{{ route('logs.index') }}" class="btn btn-primary btn-sm">Voir tous les journaux</a>
</div>

<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon kpi-icon-blue">
            <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="1" width="6" height="6" rx="1"/><rect x="9" y="1" width="6" height="6" rx="1"/><rect x="1" y="9" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg>
        </div>
        <div class="kpi-body">
            <div class="kpi-label">Actions totales</div>
            <div class="kpi-value">{{ number_format($stats['total_actions']) }}</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon kpi-icon-green">
            <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6.5"/><polyline points="8,4.5 8,8 10.5,10"/></svg>
        </div>
        <div class="kpi-body">
            <div class="kpi-label">Aujourd'hui</div>
            <div class="kpi-value" style="color:var(--accent);">{{ $stats['today'] }}</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon kpi-icon-red">
            <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6.5"/><line x1="8" y1="5" x2="8" y2="8"/><line x1="8" y1="11" x2="8.01" y2="11" stroke-width="2"/></svg>
        </div>
        <div class="kpi-body">
            <div class="kpi-label">Erreurs</div>
            <div class="kpi-value" style="color:var(--danger);">{{ $stats['errors'] }}</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon kpi-icon-amber">
            <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 1.5L1.5 13.5h13L8 1.5z"/><line x1="8" y1="6.5" x2="8" y2="9.5"/><line x1="8" y1="11.5" x2="8.01" y2="11.5" stroke-width="2"/></svg>
        </div>
        <div class="kpi-body">
            <div class="kpi-label">Accès refusés</div>
            <div class="kpi-value" style="color:var(--warning);">{{ $stats['denied'] }}</div>
        </div>
    </div>
</div>

<div class="shortcuts-grid">
    <div class="shortcut-card">
        <div class="shortcut-header">
            <div class="shortcut-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="14" height="10" rx="1.5"/><polyline points="1,4 8,9 15,4"/></svg>
            </div>
            <div class="shortcut-title">E-mails</div>
        </div>
        <p class="shortcut-desc">Créer, supprimer ou gérer les redirections.</p>
        <div class="shortcut-links">
            <a href="{{ route('email.index') }}#create-email" class="btn btn-ghost btn-sm">Créer</a>
            <a href="{{ route('email.index') }}#emails-list" class="btn btn-ghost btn-sm">Supprimer</a>
            <a href="{{ route('email.forwarders') }}" class="btn btn-ghost btn-sm">Redirections</a>
        </div>
    </div>
    <div class="shortcut-card">
        <div class="shortcut-header">
            <div class="shortcut-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><ellipse cx="8" cy="4" rx="6" ry="2"/><path d="M2 4v3c0 1.1 2.7 2 6 2s6-.9 6-2V4"/><path d="M2 7v3c0 1.1 2.7 2 6 2s6-.9 6-2V7"/><path d="M2 10v2c0 1.1 2.7 2 6 2s6-.9 6-2v-2"/></svg>
            </div>
            <div class="shortcut-title">Bases de données</div>
        </div>
        <p class="shortcut-desc">Créer et attribuer en un accès.</p>
        <div class="shortcut-links">
            <a href="{{ route('database.index') }}#create-database" class="btn btn-ghost btn-sm">Base</a>
            <a href="{{ route('database.index') }}#create-database-user" class="btn btn-ghost btn-sm">Utilisateur</a>
            <a href="{{ route('database.index') }}#database-privileges" class="btn btn-ghost btn-sm">Privilèges</a>
        </div>
    </div>
    <div class="shortcut-card">
        <div class="shortcut-header">
            <div class="shortcut-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6.5"/><ellipse cx="8" cy="8" rx="3" ry="6.5"/><line x1="1.5" y1="5.5" x2="14.5" y2="5.5"/><line x1="1.5" y1="10.5" x2="14.5" y2="10.5"/></svg>
            </div>
            <div class="shortcut-title">Domaines</div>
        </div>
        <p class="shortcut-desc">Créer addon ou sous-domaine rapidement.</p>
        <div class="shortcut-links">
            <a href="{{ route('domain.index') }}#create-addon-domain" class="btn btn-ghost btn-sm">Addon</a>
            <a href="{{ route('domain.index') }}#create-subdomain" class="btn btn-ghost btn-sm">Sous-domaine</a>
        </div>
    </div>
    <div class="shortcut-card">
        <div class="shortcut-header">
            <div class="shortcut-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="2" width="14" height="5" rx="1"/><rect x="1" y="9" width="14" height="5" rx="1"/><circle cx="12.5" cy="4.5" r="1" fill="currentColor" stroke="none"/><circle cx="12.5" cy="11.5" r="1" fill="currentColor" stroke="none"/></svg>
            </div>
            <div class="shortcut-title">Comptes FTP</div>
        </div>
        <p class="shortcut-desc">Créer ou supprimer sans navigation supplémentaire.</p>
        <div class="shortcut-links">
            <a href="{{ route('ftp.index') }}#create-ftp-account" class="btn btn-ghost btn-sm">Créer</a>
            <a href="{{ route('ftp.index') }}#ftp-accounts-list" class="btn btn-ghost btn-sm">Supprimer</a>
        </div>
    </div>
    <div class="shortcut-card">
        <div class="shortcut-header">
            <div class="shortcut-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="8" r="6.5"/><polyline points="8,4.5 8,8 10.5,10"/></svg>
            </div>
            <div class="shortcut-title">Tâches Cron</div>
        </div>
        <p class="shortcut-desc">Créer ou retirer une tâche en quelques clics.</p>
        <div class="shortcut-links">
            <a href="{{ route('cron.index') }}#create-cron-job" class="btn btn-ghost btn-sm">Créer</a>
            <a href="{{ route('cron.index') }}#cron-jobs-list" class="btn btn-ghost btn-sm">Supprimer</a>
        </div>
    </div>
    <div class="shortcut-card">
        <div class="shortcut-header">
            <div class="shortcut-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="5.5" r="3"/><path d="M1.5 14c0-3 3-5.5 6.5-5.5s6.5 2.5 6.5 5.5"/></svg>
            </div>
            <div class="shortcut-title">Utilisateurs</div>
        </div>
        <p class="shortcut-desc">Gestion des comptes et droits d'accès.</p>
        <div class="shortcut-links">
            <a href="{{ route('users.create') }}" class="btn btn-ghost btn-sm">Créer</a>
            <a href="{{ route('users.index') }}" class="btn btn-ghost btn-sm">Gérer</a>
            <a href="{{ route('permissions.index') }}" class="btn btn-ghost btn-sm">Permissions</a>
        </div>
    </div>
</div>

<div class="activity-grid">
    <div class="card" style="margin-bottom:0;">
        <div class="activity-header">
            <div class="title">Incidents récents</div>
            <a href="{{ route('logs.index') }}?status=error" class="btn btn-ghost btn-sm">Voir tout</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Module</th>
                        <th>Action</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incidents as $incident)
                        <tr data-log-row>
                            <td class="text-muted text-sm">{{ $incident->created_at->format('d/m/Y H:i:s') }}</td>
                            <td><span class="code">{{ $incident->module }}</span></td>
                            <td><span class="code">{{ $incident->action }}</span></td>
                            <td>
                                @if($incident->status === 'error')
                                    <span class="badge badge-error"><span class="status-dot status-dot-error"></span> Erreur</span>
                                @else
                                    <span class="badge badge-warning"><span class="status-dot status-dot-warning"></span> Refusé</span>
                                @endif
                            </td>
                            <td><a href="{{ route('logs.show', $incident) }}" class="btn btn-ghost btn-sm">↗</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted" style="text-align:center; padding: 24px;">Aucun incident récent.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="margin-bottom:0;">
        <div class="activity-header">
            <div class="title">Logs récents</div>
            <a href="{{ route('logs.index') }}" class="btn btn-ghost btn-sm">Voir tout</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Module</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivity as $log)
                        <tr data-log-row>
                            <td class="text-muted text-sm">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $log->user?->name ?? '<système>' }}</td>
                            <td><span class="code">{{ $log->module }}</span></td>
                            <td>
                                @if($log->status === 'success')
                                    <span class="badge badge-success"><span class="status-dot status-dot-success"></span> Succès</span>
                                @elseif($log->status === 'error')
                                    <span class="badge badge-error"><span class="status-dot status-dot-error"></span> Erreur</span>
                                @else
                                    <span class="badge badge-warning"><span class="status-dot status-dot-warning"></span> Refusé</span>
                                @endif
                            </td>
                            <td><a href="{{ route('logs.show', $log) }}" class="btn btn-ghost btn-sm">↗</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted" style="text-align:center; padding: 24px;">Aucun journal pour l'instant.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function() {
    var input = document.querySelector('[data-dashboard-search]');
    if (!input) return;
    var rows = Array.from(document.querySelectorAll('[data-log-row]'));
    input.addEventListener('input', function() {
        var q = input.value.toLowerCase().trim();
        rows.forEach(function(row) {
            row.style.display = !q || row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
})();
</script>

@endsection
