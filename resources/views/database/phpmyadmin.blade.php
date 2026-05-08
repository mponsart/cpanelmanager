@extends('layouts.app')

@section('title', 'phpMyAdmin')
@section('page-title', 'Accès phpMyAdmin')

@section('content')

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:16px;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error" style="margin-bottom:16px;">{{ session('error') }}</div>
@endif

<div class="card" style="max-width:600px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border);">
        <div style="width:36px;height:36px;border-radius:8px;background:rgba(26,115,232,0.08);border:1px solid rgba(26,115,232,0.16);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="18" height="18" viewBox="0 0 16 16" fill="none" stroke="var(--primary,#1a73e8)" stroke-width="1.5"><ellipse cx="8" cy="4" rx="6" ry="2.5"/><path d="M2 4v4c0 1.38 2.69 2.5 6 2.5s6-1.12 6-2.5V4"/><path d="M2 8v4c0 1.38 2.69 2.5 6 2.5s6-1.12 6-2.5V8"/></svg>
        </div>
        <div>
            <div style="font-size:15px;font-weight:700;color:var(--text);">phpMyAdmin</div>
            <div style="font-size:12px;color:var(--text-muted);">Accès direct à la gestion des bases de données</div>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Statut du compte --}}
        <div style="background:var(--panel-soft);border:1px solid var(--border);border-radius:8px;padding:14px 16px;display:flex;align-items:center;gap:12px;">
            <div style="width:8px;height:8px;border-radius:50%;background:{{ $hasSetup ? 'var(--success,#1e8e3e)' : '#d1d5db' }};flex-shrink:0;"></div>
            <div>
                <div style="font-size:13px;font-weight:600;color:var(--text);">
                    Utilisateur MySQL : <code class="code">{{ $user }}</code>
                </div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                    @if($hasSetup)
                        Compte configuré — prêt à se connecter.
                    @else
                        Compte non initialisé — cliquez sur "Initialiser" pour créer le compte MySQL.
                    @endif
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            @if($hasSetup)
                <a href="{{ route('phpmyadmin.connect') }}" class="btn btn-primary" target="_blank">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1v-3"/><polyline points="9,1 15,1 15,7"/><line x1="15" y1="1" x2="7" y2="9"/></svg>
                    Ouvrir phpMyAdmin
                </a>
            @endif

            @can('create_db')
            <form action="{{ route('phpmyadmin.setup') }}" method="POST">
                @csrf
                <button type="submit" class="btn {{ $hasSetup ? 'btn-ghost' : 'btn-primary' }}"
                    onclick="return confirm('{{ $hasSetup ? 'Régénérer le mot de passe de l\'utilisateur MySQL ?' : 'Créer l\'utilisateur MySQL gowo3083_phpmyadmin ?' }}')">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1.5 8a6.5 6.5 0 0112.48-2.5M14.5 8a6.5 6.5 0 01-12.48 2.5"/><polyline points="14,2 14,5.5 10.5,5.5"/><polyline points="2,14 2,10.5 5.5,10.5"/></svg>
                    {{ $hasSetup ? 'Régénérer le mot de passe' : 'Initialiser le compte' }}
                </button>
            </form>
            @endcan
        </div>

        <p class="text-muted" style="font-size:11px;line-height:1.6;margin:0;">
            Le compte <strong>{{ $user }}</strong> est un utilisateur MySQL dédié à l'accès phpMyAdmin.
            Son mot de passe est géré automatiquement et stocké de façon sécurisée dans la configuration du serveur.
        </p>
    </div>
</div>

@endsection
