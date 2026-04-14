@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="stats-grid">
    <div class="stat-card" style="border-left:3px solid var(--accent);">
        <div class="stat-label">Actions totales</div>
        <div class="stat-value">{{ number_format($stats['total_actions']) }}</div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--accent);">
        <div class="stat-label">Aujourd'hui</div>
        <div class="stat-value" style="color:var(--accent);">{{ $stats['today'] }}</div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--danger);">
        <div class="stat-label">Erreurs</div>
        <div class="stat-value" style="color:var(--danger);">{{ $stats['errors'] }}</div>
    </div>
    <div class="stat-card" style="border-left:3px solid var(--warning);">
        <div class="stat-label">Accès refusés</div>
        <div class="stat-value" style="color:var(--warning);">{{ $stats['denied'] }}</div>
    </div>
</div>

<div class="card">
    <div class="card-title">Dernières actions</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Module</th>
                    <th>Action</th>
                    <th>Cible</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLogs as $log)
                    <tr>
                        <td class="text-muted text-sm">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $log->user?->name ?? '<système>' }}</td>
                        <td><span class="code">{{ $log->module }}</span></td>
                        <td><span class="code">{{ $log->action }}</span></td>
                        <td class="text-muted">{{ $log->target ?? '—' }}</td>
                        <td>
                            @if($log->status === 'success')
                                <span class="badge badge-success">Succès</span>
                            @elseif($log->status === 'error')
                                <span class="badge badge-error">Erreur</span>
                            @else
                                <span class="badge badge-warning">Refusé</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted" style="text-align:center; padding: 24px;">Aucun journal pour l'instant.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 14px;">
        <a href="{{ route('logs.index') }}" class="btn btn-ghost btn-sm">Voir tous les journaux →</a>
    </div>
</div>

@endsection
