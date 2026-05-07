@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@php
    $user = auth()->user();
    $can = fn (string $permission) => $user && ($user->isSuperAdmin() || $user->hasPermission($permission));

    $incidents = $recentLogs->whereIn('status', ['error', 'denied'])->take(8);
    $recentActivity = $recentLogs->reject(fn ($log) => in_array($log->status, ['error', 'denied'], true))->take(10);
@endphp

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
    @if($can('view_associations'))
    <div class="shortcut-card">
        <div class="shortcut-header">
            <div class="shortcut-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 3.5A1.5 1.5 0 013.5 2h3l1.5 2h4.5A1.5 1.5 0 0114 5.5v7a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 012 12.5v-9z"/></svg>
            </div>
            <div class="shortcut-title">Associations</div>
        </div>
        <p class="shortcut-desc">Créer, suspendre et gérer les quotas des instances.</p>
        <div class="shortcut-links">
            <a href="{{ route('association.index') }}" class="btn btn-ghost btn-sm">Ouvrir</a>
        </div>
    </div>
    @endif

    @if($can('access_cpanel'))
    <div class="shortcut-card">
        <div class="shortcut-header">
            <div class="shortcut-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="2" width="14" height="5" rx="1"/><rect x="1" y="9" width="14" height="5" rx="1"/><circle cx="12.5" cy="4.5" r="1" fill="currentColor" stroke="none"/><circle cx="12.5" cy="11.5" r="1" fill="currentColor" stroke="none"/></svg>
            </div>
            <div class="shortcut-title">Accès cPanel</div>
        </div>
        <p class="shortcut-desc">Session encadrée et rotation automatique des accès.</p>
        <div class="shortcut-links">
            <a href="{{ route('cpanel.index') }}" class="btn btn-ghost btn-sm">Ouvrir</a>
        </div>
    </div>
    @endif

    @if($can('manage_users'))
    <div class="shortcut-card">
        <div class="shortcut-header">
            <div class="shortcut-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="8" cy="5.5" r="3"/><path d="M1.5 14c0-3 3-5.5 6.5-5.5s6.5 2.5 6.5 5.5"/></svg>
            </div>
            <div class="shortcut-title">Utilisateurs</div>
        </div>
        <p class="shortcut-desc">Gestion des comptes techniciens et statuts d'accès.</p>
        <div class="shortcut-links">
            <a href="{{ route('users.index') }}" class="btn btn-ghost btn-sm">Gérer</a>
            <a href="{{ route('users.create') }}" class="btn btn-ghost btn-sm">Créer</a>
        </div>
    </div>

    <div class="shortcut-card">
        <div class="shortcut-header">
            <div class="shortcut-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 1l6 3v4c0 3.5-2.5 6.5-6 7.5C4.5 14.5 2 11.5 2 8V4l6-3z"/></svg>
            </div>
            <div class="shortcut-title">Permissions</div>
        </div>
        <p class="shortcut-desc">Attribution des droits modules et contrôle des accès.</p>
        <div class="shortcut-links">
            <a href="{{ route('permissions.index') }}" class="btn btn-ghost btn-sm">Ouvrir</a>
        </div>
    </div>

    <div class="shortcut-card">
        <div class="shortcut-header">
            <div class="shortcut-icon">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 2h7l3 3v9a1 1 0 01-1 1H3a1 1 0 01-1-1V3a1 1 0 011-1z"/><path d="M10 2v3h3"/><line x1="4.5" y1="8" x2="11.5" y2="8"/><line x1="4.5" y1="10.5" x2="11.5" y2="10.5"/></svg>
            </div>
            <div class="shortcut-title">Journaux</div>
        </div>
        <p class="shortcut-desc">Suivi des actions, incidents et audits de sécurité.</p>
        <div class="shortcut-links">
            <a href="{{ route('logs.index') }}" class="btn btn-ghost btn-sm">Consulter</a>
        </div>
    </div>
    @endif
</div>

@if(!$can('view_associations') && !$can('access_cpanel') && !$can('manage_users'))
<div class="card">
    <div class="alert alert-warning" style="margin-bottom:0;">
        Aucune fonctionnalité n'est actuellement assignée à votre profil. Contactez un administrateur.
    </div>
</div>
@endif

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
                            <td><a href="{{ route('logs.show', $incident) }}" class="btn btn-ghost btn-sm" aria-label="Voir les détails">↗</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="table-empty">Aucun incident récent.</td></tr>
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
                            <td><a href="{{ route('logs.show', $log) }}" class="btn btn-ghost btn-sm" aria-label="Voir les détails">↗</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="table-empty">Aucun journal pour l'instant.</td></tr>
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
