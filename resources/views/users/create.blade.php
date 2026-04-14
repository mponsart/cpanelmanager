@extends('layouts.app')

@section('title', 'Nouvel utilisateur')
@section('page-title', 'Créer un utilisateur')

@section('content')

<div class="page-header">
    <h1>Nouvel utilisateur</h1>
    <a href="{{ route('users.index') }}" class="btn btn-ghost">← Retour</a>
</div>

<div class="card" style="max-width: 560px;">
    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nom complet</label>
            <input type="text" name="name" value="{{ old('name') }}" required maxlength="100" autofocus>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Adresse e-mail</label>
            <input type="email" name="email" value="{{ old('email') }}" required maxlength="255">
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
            <p class="text-muted text-sm mt-1">L'utilisateur se connectera via Google avec cette adresse.</p>
        </div>

        <div class="form-group">
            <label>Statut</label>
            <select name="status">
                <option value="active"   {{ old('status', 'active') === 'active'   ? 'selected' : '' }}>Actif</option>
                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
            </select>
            @error('status')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
            <a href="{{ route('users.index') }}" class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</div>

@endsection
