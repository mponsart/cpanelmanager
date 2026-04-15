@extends('layouts.app')

@section('title', 'Bande passante — ' . $domain)
@section('page-title', 'Bande passante')

@section('content')

<div class="back-link-wrap">
    <a href="{{ route('stats.index') }}" class="back-link">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="10,2 4,8 10,14"/></svg>
        Retour aux statistiques
    </a>
</div>

<div class="card mb-3">
    <div class="domain-summary">
        <div>
            <div style="font-size:1.1rem;font-weight:700;color:var(--text);">{{ $domain }}</div>
            <div class="text-muted text-sm" style="margin-top:4px;">Détail de la consommation bande passante</div>
        </div>
        <div style="text-align:right;">
            @php
                $totalMb = round($totalBytes / 1048576, 2);
                $totalDisplay = $totalMb >= 1024 ? round($totalMb/1024, 2).' Go' : $totalMb.' Mo';
            @endphp
            <div style="font-size:1.5rem;font-weight:700;color:var(--accent);">{{ $totalDisplay }}</div>
            <div class="text-muted text-sm">consommation totale</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-title">Consommation par protocole</div>
    @if(!empty($protocols))
        @php
            $maxProto = max(array_values($protocols) ?: [1]);
            $protoLabels = [
                'http' => ['nom' => 'HTTP', 'color' => 'var(--accent)'],
                'https' => ['nom' => 'HTTPS', 'color' => 'var(--success)'],
                'ftp' => ['nom' => 'FTP', 'color' => 'var(--warning)'],
                'smtp' => ['nom' => 'SMTP', 'color' => '#f472b6'],
                'pop3' => ['nom' => 'POP3', 'color' => '#22d3ee'],
                'imap' => ['nom' => 'IMAP', 'color' => '#a78bfa'],
            ];
        @endphp
        <div class="table-wrap">
            <table>
                <thead><tr><th>Protocole</th><th style="width:120px;text-align:right;">Consommation</th><th style="width:200px;"></th></tr></thead>
                <tbody>
                    @foreach($protocols as $proto => $bytes)
                        @php
                            $mb = round($bytes / 1048576, 2);
                            $display = $mb >= 1024 ? round($mb/1024, 2).' Go' : $mb.' Mo';
                            $barPercent = $maxProto > 0 ? round(($bytes / $maxProto) * 100) : 0;
                            $info = $protoLabels[strtolower($proto)] ?? ['nom' => strtoupper($proto), 'color' => 'var(--text-muted)'];
                        @endphp
                        <tr>
                            <td style="font-weight:500;">
                                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $info['color'] }};margin-right:8px;"></span>
                                {{ $info['nom'] }}
                            </td>
                            <td style="text-align:right;font-variant-numeric:tabular-nums;">{{ $display }}</td>
                            <td style="padding-left:12px;">
                                <div style="background:var(--border);border-radius:4px;height:8px;overflow:hidden;">
                                    <div style="width:{{ $barPercent }}%;height:100%;background:{{ $info['color'] }};border-radius:4px;transition:width .3s;"></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted" style="padding:12px 0;">Aucune donnée de bande passante disponible pour ce domaine.</p>
    @endif
</div>

@endsection
