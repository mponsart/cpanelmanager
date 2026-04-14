@extends('layouts.app')

@section('title', 'Statistiques')
@section('page-title', 'Statistiques cPanel')

@section('content')

@if(!empty($stats))
<div class="stats-grid">
    @foreach($stats as $stat)
        @php
            $colors = [
                'bandwidthusage' => 'var(--accent)',
                'diskusage'      => 'var(--success)',
                'emailaccounts'  => '#a78bfa',
                'mysqldatabases' => 'var(--warning)',
                'ftpaccounts'    => '#22d3ee',
                'subdomains'     => '#f472b6',
            ];
            $color = $colors[$stat['id'] ?? ''] ?? 'var(--text-muted)';
        @endphp
        <div class="stat-card" style="border-left: 3px solid {{ $color }};">
            <div class="stat-label">{{ $stat['item'] ?? $stat['name'] ?? '—' }}</div>
            <div class="stat-value" style="font-size:20px; color:{{ $color }};">{{ $stat['count'] ?? '—' }}</div>
            @if(isset($stat['max']) && $stat['max'] !== 'unlimited')
                <div class="text-muted text-sm mt-1">max : {{ $stat['max'] }}</div>
            @endif
        </div>
    @endforeach
</div>
@endif

<div class="form-row form-row-2">
    <div class="card">
        <div class="card-title">Utilisation disque</div>
        @if(!empty($diskUsage))
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Quota utilisé</th><th>Quota total</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>{{ $diskUsage['megabytes_used'] ?? '—' }} Mo</td>
                            <td>{{ isset($diskUsage['megabyte_limit']) && $diskUsage['megabyte_limit'] ? $diskUsage['megabyte_limit'].' Mo' : 'Illimité' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">Non disponible.</p>
        @endif
    </div>

    <div class="card">
        <div class="card-title">Bande passante</div>
        @if(!empty($bandwidth))
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Domaine</th><th>Consommation</th></tr></thead>
                    <tbody>
                        @foreach($bandwidth as $domain => $bytes)
                            <tr>
                                <td class="text-sm">{{ $domain }}</td>
                                <td class="text-muted">
                                    @php $mb = round($bytes / 1048576, 2); @endphp
                                    {{ $mb >= 1024 ? round($mb/1024, 2).' Go' : $mb.' Mo' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">Données non disponibles.</p>
        @endif
    </div>
</div>

@endsection
