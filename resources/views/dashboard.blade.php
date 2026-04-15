@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@php
    $incidents = $recentLogs->whereIn('status', ['error', 'denied'])->take(8);
    $recentActivity = $recentLogs->reject(fn ($log) => in_array($log->status, ['error', 'denied'], true))->take(10);
@endphp

<style>
    .dashboard-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .dashboard-search {
        min-width: 260px;
        max-width: 420px;
        width: 100%;
    }

    .dashboard-search input { background: #fff; }

    .dashboard-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .quick-card {
        border-radius: 14px;
        border: 1px solid var(--border);
        background: linear-gradient(180deg, #fff 0%, #f8fbff 100%);
        padding: 14px;
        box-shadow: var(--shadow-sm);
    }

    .quick-card .title { font-size: 14px; font-weight: 700; margin-bottom: 6px; }
    .quick-card p { color: var(--text-muted); font-size: 12px; margin-bottom: 10px; }

    .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 18px;
    }

    .status-dot {
        width: 9px;
        height: 9px;
        border-radius: 999px;
        display: inline-block;
    }

    .status-dot-error { background: var(--danger); }
    .status-dot-warning { background: var(--warning); }
    .status-dot-success { background: var(--success); }

    @media (max-width: 980px) {
        .dashboard-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="dashboard-top">
    <div class="dashboard-search">
        <input type="search" placeholder="Rechercher dans les incidents et logs..." data-dashboard-search aria-label="Rechercher dans les incidents et logs">
    </div>
    <a href="{{ route('logs.index') }}" class="btn btn-primary">Voir tous les journaux</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Actions totales</div>
        <div class="stat-value">{{ number_format($stats['total_actions']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Aujourd'hui</div>
        <div class="stat-value" style="color:var(--accent);">{{ $stats['today'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Erreurs</div>
        <div class="stat-value" style="color:var(--danger);">{{ $stats['errors'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Accès refusés</div>
        <div class="stat-value" style="color:var(--warning);">{{ $stats['denied'] }}</div>
    </div>
</div>

<div class="dashboard-actions">
    <div class="quick-card">
        <div class="title">Gestion E-mails</div>
        <p>Créer, réinitialiser et supprimer rapidement.</p>
        <a href="{{ route('email.index') }}" class="btn btn-ghost btn-sm">Ouvrir</a>
    </div>
    <div class="quick-card">
        <div class="title">Bases de données</div>
        <p>Créer base, utilisateur et privilèges.</p>
        <a href="{{ route('database.index') }}" class="btn btn-ghost btn-sm">Ouvrir</a>
    </div>
    <div class="quick-card">
        <div class="title">Domaines</div>
        <p>Gérer domaines addon et sous-domaines.</p>
        <a href="{{ route('domain.index') }}" class="btn btn-ghost btn-sm">Ouvrir</a>
    </div>
    <div class="quick-card">
        <div class="title">Comptes FTP</div>
        <p>Ajouter ou supprimer les accès FTP.</p>
        <a href="{{ route('ftp.index') }}" class="btn btn-ghost btn-sm">Ouvrir</a>
    </div>
    <div class="quick-card">
        <div class="title">Tâches Cron</div>
        <p>Piloter les exécutions planifiées.</p>
        <a href="{{ route('cron.index') }}" class="btn btn-ghost btn-sm">Ouvrir</a>
    </div>
    <div class="quick-card">
        <div class="title">Utilisateurs</div>
        <p>Accès techniciens et permissions.</p>
        <a href="{{ route('users.index') }}" class="btn btn-ghost btn-sm">Ouvrir</a>
    </div>
</div>

<div class="dashboard-grid">
    <div class="card">
        <div class="card-title">Incidents récents</div>
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
                            <td><a href="{{ route('logs.show', $incident) }}" class="btn btn-ghost btn-sm">Détails</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted" style="text-align:center; padding: 24px;">Aucun incident récent.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Logs récents</div>
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
                            <td><a href="{{ route('logs.show', $log) }}" class="btn btn-ghost btn-sm">Détails</a></td>
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
