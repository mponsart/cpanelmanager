@extends('layouts.app')

@section('title', 'Modifier ' . $user->name)
@section('page-title', 'Modifier : ' . $user->name)

@section('content')

<div class="page-header">
    <h1>Modifier l'utilisateur</h1>
    <a href="{{ route('users.show', $user) }}" class="btn btn-ghost">← Retour</a>
</div>

<div class="card" style="max-width: 560px;">
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nom complet</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="100">
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Adresse e-mail</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255">
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Statut</label>
            <select name="status">
                <option value="active"   {{ old('status', $user->status) === 'active'   ? 'selected' : '' }}>Actif</option>
                <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
            </select>
            @error('status')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('users.show', $user) }}" class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</div>

@endsection
