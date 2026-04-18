@extends('layouts.app')

@section('title', 'Détail du journal')
@section('page-title', 'Détail du journal #' . $log->id)

@section('content')

<div class="page-header">
    <h1>Journal #{{ $log->id }}</h1>
    <a href="{{ route('logs.index') }}" class="btn btn-ghost">← Retour</a>
</div>

<div class="card">
    <div class="form-row form-row-2">
        <div>
            <label>Date</label>
            <p>{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
        </div>
        <div>
            <label>Statut</label>
            <p>
                @if($log->status === 'success')
                    <span class="badge badge-success">Succès</span>
                @elseif($log->status === 'error')
                    <span class="badge badge-error">Erreur</span>
                @else
                    <span class="badge badge-warning">Refusé</span>
                @endif
            </p>
        </div>
        <div>
            <label>Utilisateur</label>
            <p>{{ $log->user?->name ?? '(système)' }}</p>
        </div>
        <div>
            <label>Module</label>
            <p><span class="code">{{ $log->module }}</span></p>
        </div>
        <div>
            <label>Action</label>
            <p><span class="code">{{ $log->action }}</span></p>
        </div>
        <div>
            <label>Cible</label>
            <p>{{ $log->target ?? '—' }}</p>
        </div>
        <div>
            <label>Adresse IP</label>
            <p class="text-muted">{{ $log->ip ?? '—' }}</p>
        </div>
        <div>
            <label>User Agent</label>
            <p class="text-muted text-sm" style="word-break: break-all;">{{ $log->user_agent ?? '—' }}</p>
        </div>
    </div>

    @if($log->error_message)
        <div class="mt-3">
            <label>Message d'erreur</label>
            <div class="alert alert-error mt-1">{{ $log->error_message }}</div>
        </div>
    @endif

    @if($log->payload)
        @if($log->action === 'cpanel_session_report')
        <div class="mt-3">
            <label>Rapport de session cPanel</label>
            <div style="background:var(--panel-soft);border:1px solid var(--border);border-radius:8px;padding:16px;margin-top:6px;">
                <div style="font-size:13px;color:var(--text);line-height:1.7;white-space:pre-wrap;">{{ $log->payload['description'] ?? '—' }}</div>
                <div style="display:flex;gap:16px;margin-top:12px;padding-top:12px;border-top:1px solid var(--border);font-size:12px;color:var(--text-muted);">
                    @if(!empty($log->payload['session_duration']))
                    <span>Durée : <strong style="color:var(--text);">{{ $log->payload['session_duration'] }}</strong></span>
                    @endif
                    @if(!empty($log->payload['attested']))
                    <span style="color:#166534;">✓ Attesté sur l'honneur</span>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="mt-3">
            <label>Payload</label>
            <pre style="background: var(--panel-soft); padding: 14px; border-radius: var(--radius); overflow-x: auto; font-size: 13px; margin-top: 6px; border: 1px solid var(--border);">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
        @endif
    @endif
</div>

@endsection
