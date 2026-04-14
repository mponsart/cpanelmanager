@extends('layouts.app')

@section('title', 'Permissions')
@section('page-title', 'Permissions disponibles')

@section('content')

<div class="page-header">
    <h1>Permissions du système</h1>
</div>

@foreach($permissions as $module => $perms)
<div class="card">
    <div class="card-title">{{ ucfirst($module) }}</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Clé</th>
                    <th>Libellé</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach($perms as $perm)
                    <tr>
                        <td><span class="code">{{ $perm->key }}</span></td>
                        <td>{{ $perm->label }}</td>
                        <td class="text-muted text-sm">{{ $perm->description ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach

@endsection
