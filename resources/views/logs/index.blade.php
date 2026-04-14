@extends('layouts.app')

@section('title', 'Journaux')
@section('page-title', 'Journaux d\'actions')

@section('content')

<div class="card">
    <div class="card-title">Filtres</div>
    <form method="GET" action="{{ route('logs.index') }}">
        <div class="form-row form-row-3">
            <div class="form-group">
                <label>Module</label>
                <select name="module">
                    <option value="">Tous</option>
                    @foreach($modules as $m)
                        <option value="{{ $m }}" {{ request('module') === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Statut</label>
                <select name="status">
                    <option value="">Tous</option>
                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Succès</option>
                    <option value="error"   {{ request('status') === 'error'   ? 'selected' : '' }}>Erreur</option>
                    <option value="denied"  {{ request('status') === 'denied'  ? 'selected' : '' }}>Refusé</option>
                </select>
            </div>
            <div class="form-group">
                <label>&nbsp;</label>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="{{ route('logs.index') }}" class="btn btn-ghost">Réinitialiser</a>
                </div>
            </div>
        </div>
        <div class="form-row form-row-2">
            <div class="form-group">
                <label>Date début</label>
                <input type="date" name="from" value="{{ request('from') }}">
            </div>
            <div class="form-group">
                <label>Date fin</label>
                <input type="date" name="to" value="{{ request('to') }}">
            </div>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Module</th>
                    <th>Action</th>
                    <th>Cible</th>
                    <th>IP</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="text-muted text-sm">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $log->user?->name ?? '—' }}</td>
                        <td><span class="code">{{ $log->module }}</span></td>
                        <td><span class="code">{{ $log->action }}</span></td>
                        <td class="text-muted text-sm">{{ $log->target ?? '—' }}</td>
                        <td class="text-muted text-sm">{{ $log->ip ?? '—' }}</td>
                        <td>
                            @if($log->status === 'success')
                                <span class="badge badge-success">Succès</span>
                            @elseif($log->status === 'error')
                                <span class="badge badge-error">Erreur</span>
                            @else
                                <span class="badge badge-warning">Refusé</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('logs.show', $log) }}" class="btn btn-ghost btn-sm">Détails</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-muted" style="text-align:center; padding: 24px;">Aucun journal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $logs->links() }}
    </div>
</div>

@endsection
