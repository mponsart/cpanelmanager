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
            @php
                $used  = $diskUsage['megabytes_used'] ?? 0;
                $limit = $diskUsage['megabyte_limit'] ?? 0;
                $unlimited = !$limit || $limit == 0;
                $percent = $unlimited ? 0 : round(($used / $limit) * 100, 1);
            @endphp
            <div class="disk-usage-row">
                <div class="disk-usage-details">
                    <div style="font-size:1.5rem;font-weight:700;color:var(--text);">
                        {{ number_format($used, 1) }} Mo
                    </div>
                    <div class="text-muted text-sm" style="margin-top:4px;">
                        @if($unlimited)
                            Quota : <strong style="color:var(--success);">Illimité</strong>
                        @else
                            sur {{ number_format($limit, 0) }} Mo ({{ $percent }}%)
                        @endif
                    </div>
                </div>
                @unless($unlimited)
                <div class="disk-usage-bar-wrap">
                    <div style="background:var(--border);border-radius:6px;height:10px;overflow:hidden;">
                        <div style="width:{{ min($percent, 100) }}%;height:100%;background:{{ $percent > 90 ? 'var(--danger)' : ($percent > 70 ? 'var(--warning)' : 'var(--success)') }};border-radius:6px;transition:width .3s;"></div>
                    </div>
                </div>
                @endunless
            </div>
        @else
            <p class="text-muted">Non disponible.</p>
        @endif
    </div>

    <div class="card">
        <div class="card-title">Bande passante par domaine</div>
        @if(!empty($bandwidth))
            @php
                $maxBytes = is_array($bandwidth) ? max(array_values($bandwidth) ?: [1]) : 1;
            @endphp
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Domaine</th><th style="width:120px;text-align:right;">Consommation</th><th style="width:180px;"></th></tr></thead>
                    <tbody>
                        @foreach($bandwidth as $domain => $bytes)
                            @php
                                $mb = round($bytes / 1048576, 2);
                                $display = $mb >= 1024 ? round($mb/1024, 2).' Go' : $mb.' Mo';
                                $barPercent = $maxBytes > 0 ? round(($bytes / $maxBytes) * 100) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('stats.domain', $domain) }}" style="color:var(--accent);text-decoration:none;font-weight:500;">
                                        {{ $domain }}
                                    </a>
                                </td>
                                <td style="text-align:right;font-variant-numeric:tabular-nums;">{{ $display }}</td>
                                <td style="padding-left:12px;">
                                    <div style="background:var(--border);border-radius:4px;height:8px;overflow:hidden;">
                                        <div style="width:{{ $barPercent }}%;height:100%;background:var(--accent);border-radius:4px;transition:width .3s;"></div>
                                    </div>
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
