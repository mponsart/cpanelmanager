@extends('layouts.app')

@section('title', 'Bases de données')
@section('page-title', 'Bases de données MySQL')

@section('content')

<div class="card mb-3" style="display:flex;flex-direction:row;align-items:center;justify-content:space-between;padding:16px 20px;">
    <div>
        <div style="font-weight:600;font-size:1rem;">phpMyAdmin</div>
        <div class="text-muted" style="font-size:0.85rem;">Accéder à l'interface de gestion des bases de données</div>
    </div>
    <a href="{{ route('database.phpmyadmin') }}" target="_blank" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:8px;">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 3H3v10h10v-3"/><path d="M9 2h5v5"/><path d="M14 2L7 9"/></svg>
        Ouvrir phpMyAdmin
    </a>
</div>

<div class="form-row form-row-2 mb-3">
    <div class="card">
        <div class="card-title">Créer une base de données</div>
        <form action="{{ route('database.create-db') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nom de la base</label>
                <input type="text" name="name" required maxlength="64" pattern="[a-zA-Z0-9_]+" placeholder="ma_base">
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>

    <div class="card">
        <div class="card-title">Créer un utilisateur MySQL</div>
        <form action="{{ route('database.create-user') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nom d'utilisateur</label>
                <input type="text" name="name" required maxlength="16" pattern="[a-zA-Z0-9_]+" placeholder="mon_user">
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required minlength="12" maxlength="255">
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-title">Assigner des privilèges</div>
    <form action="{{ route('database.privileges') }}" method="POST">
        @csrf
        <div class="form-row form-row-3">
            <div class="form-group">
                <label>Base de données</label>
                <select name="database">
                    @foreach($databases as $db)
                        <option value="{{ $db['database'] ?? $db }}">{{ $db['database'] ?? $db }}</option>
                    @endforeach
                </select>
                @error('database')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Utilisateur MySQL</label>
                <select name="dbuser">
                    @foreach($dbUsers as $u)
                        <option value="{{ $u['user'] ?? $u }}">{{ $u['user'] ?? $u }}</option>
                    @endforeach
                </select>
                @error('dbuser')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Privilèges</label>
                <select name="privileges">
                    <option value="ALL PRIVILEGES">Tous (ALL PRIVILEGES)</option>
                    <option value="SELECT, INSERT, UPDATE, DELETE">Lecture/Écriture</option>
                    <option value="SELECT">Lecture seule</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Assigner</button>
    </form>
</div>

<div class="form-row form-row-2">
    <div class="card">
        <div class="card-title">Bases de données ({{ count($databases) }})</div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nom</th><th>Taille</th></tr></thead>
                <tbody>
                    @forelse($databases as $db)
                        <tr>
                            <td>{{ $db['database'] ?? $db }}</td>
                            <td class="text-muted">{{ isset($db['disk_usage']) ? number_format($db['disk_usage'] / 1024, 2) . ' Mo' : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted" style="text-align:center;padding:16px;">Aucune base de données.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Utilisateurs MySQL ({{ count($dbUsers) }})</div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nom</th></tr></thead>
                <tbody>
                    @forelse($dbUsers as $u)
                        <tr><td>{{ $u['user'] ?? $u }}</td></tr>
                    @empty
                        <tr><td class="text-muted" style="text-align:center;padding:16px;">Aucun utilisateur.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
